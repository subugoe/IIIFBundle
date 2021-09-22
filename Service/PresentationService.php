<?php

declare(strict_types=1);

namespace Subugoe\IIIFBundle\Service;

use DateTime;
use Exception;
use InvalidArgumentException;
use Mimey\MimeTypes;
use Subugoe\IIIFBundle\Exception\DataException;
use Subugoe\IIIFBundle\Exception\IIIFException;
use Subugoe\IIIFBundle\Exception\MalformedDocumentException;
use Subugoe\IIIFModel\Model\LogicalStructure;
use Subugoe\IIIFModel\Model\PhysicalStructure;
use Subugoe\IIIFModel\Model\Presentation\AnnotationList;
use Subugoe\IIIFModel\Model\Presentation\Canvas;
use Subugoe\IIIFModel\Model\Presentation\Collection;
use Subugoe\IIIFModel\Model\Presentation\Collections;
use Subugoe\IIIFModel\Model\Presentation\Document;
use Subugoe\IIIFModel\Model\Presentation\GenericResource;
use Subugoe\IIIFModel\Model\Presentation\Image;
use Subugoe\IIIFModel\Model\Presentation\Metadata;
use Subugoe\IIIFModel\Model\Presentation\Range;
use Subugoe\IIIFModel\Model\Presentation\ResourceData;
use Subugoe\IIIFModel\Model\Presentation\Sequence;
use Subugoe\IIIFModel\Model\Presentation\Service;
use Subugoe\IIIFModel\Model\Presentation\Structure;
use Subugoe\IIIFModel\Service\PresentationServiceInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouterInterface;

class PresentationService implements PresentationServiceInterface
{
    public const CONTEXT_IMAGE = 'images';
    public const CONTEXT_MANIFESTS = 'manifests';

    private const DEFAULT_HEIGHT = 400;
    private const DEFAULT_WIDTH = 300;

    private RouterInterface $router;

    private array $imageConfiguration;

    private array $presentationConfiguration;

    private FileServiceInterface $fileService;

    /**
     * PresentationService constructor.
     */
    public function __construct(RouterInterface $router, array $imageConfiguration, array $presentationConfiguration, FileServiceInterface $fileService)
    {
        $this->router = $router;
        $this->imageConfiguration = $imageConfiguration;
        $this->presentationConfiguration = $presentationConfiguration;
        $this->fileService = $fileService;
    }

    /**
     * @throws IIIFException
     */
    public function getManifest(\Subugoe\IIIFModel\Model\Document $document): Document
    {
        $this->router->setContext($this->setRoutingContext(self::CONTEXT_MANIFESTS));

        $manifest = new Document();

        $metadata = $this->getMetadata($document);
        $thumbnail = $this->getThumbnail($document);
        $logo = $this->getLogo();
        $sequences = $this->getSequences($document);
        $structures = $this->getStructures($document);
        $attribution = $this->getAttribution($document);
        $manifest
            ->setId($this->router->generate(
                '_detail',
                [
                    'id' => $document->getId(),
                ],
                UrlGeneratorInterface::ABSOLUTE_URL)
            )
            ->setLabel($document->getTitle()[0])
            ->setNavDate($this->getNavDate($document))
            ->setThumbnail($thumbnail)
            ->setMetadata($metadata)
            ->setAttribution($attribution)
            ->setLogo($logo)
            ->setSequences([$sequences])
            ->setStructures($structures)
            ->setSeeAlso($document->getSeeAlso())
            ->setRendering($document->getRenderings())
            ->setRelated($document->getRelated())
        ;

        if (!empty($document->getLicense())) {
            $manifest->setLicense($document->getLicense());
        }

        if (!empty($document->getDescription())) {
            $manifest->setDescription($document->getDescription());
        }
        /** @var Document $parent */
        foreach ($document->getParents() as $parent) {
            $manifest->setWithin($this->router->generate('subugoe_iiif_manifest', ['id' => $parent->getId()], UrlGeneratorInterface::ABSOLUTE_URL));
        }

        return $manifest;
    }

    /**
     * @throws IIIFException
     */
    public function getAnnotationList(\Subugoe\IIIFModel\Model\Document $document, string $name): AnnotationList
    {
        $this->router->setContext($this->setRoutingContext(self::CONTEXT_MANIFESTS));

        $annotationList = new AnnotationList();
        $annotationList->setId($this->router->generate('subugoe_iiif_annotation-list', [
            'id' => $document->getId(),
            'name' => $name,
        ], UrlGeneratorInterface::ABSOLUTE_URL));

        $resources = [];
        $resourceData = new ResourceData();
        $resourceData
            ->setType('dctypes:Text')
            ->setId($document->getPhysicalStructure($this->getPagePositionByIdentifier($document, $name))->getAnnotation())
            ->setFormat('text/html');

        $resource = new GenericResource();
        $resource
            ->setResource($resourceData);

        $resources[] = $resource;
        $annotationList->setResources($resources);

        return $annotationList;
    }

    public function getCollection(Collection $collection): Collection
    {
        return $collection;
    }

    public function getCollections(Collections $collections): Collections
    {
        return $collections;
    }

    /**
     * @throws IIIFException
     */
    public function getRange(\Subugoe\IIIFModel\Model\Document $document, string $name): Range
    {
        $this->router->setContext($this->setRoutingContext(self::CONTEXT_MANIFESTS));

        $range = new Range();

        $range
            ->setId($this->router->generate('subugoe_iiif_range', ['id' => $document->getId(), 'range' => $name], UrlGeneratorInterface::ABSOLUTE_URL))
            ->setLabel($this->getLabelForRange($document, $name))
            ->setMembers($this->getMembersForRange($document, $name));

        return $range;
    }

    /**
     * @throws IIIFException
     */
    public function getCanvas(\Subugoe\IIIFModel\Model\Document $document, string $canvasId, int $physicalStructureId = -1): Canvas
    {
        $this->router->setContext($this->setRoutingContext(self::CONTEXT_MANIFESTS));
        $physicalStructure = $document->getPhysicalStructure($physicalStructureId);

        $images = $this->getImages($document, $canvasId);

        if (-1 !== $physicalStructureId) {
            $label = $physicalStructure->getLabel();
        } else {
            $label = $this->getLabelForCanvas($document, $canvasId);
        }

        $canvas = new Canvas();
        $canvas
            ->setId($this->router->generate('subugoe_iiif_canvas', [
                'id' => $document->getId(),
                'canvas' => $canvasId,
            ],
                UrlGeneratorInterface::ABSOLUTE_URL))
            ->setLabel($label)
            ->setImages($images);

        if (!empty($physicalStructure->getAnnotation())) {
            $annotationList = new AnnotationList();
            $annotationList
                ->setId($this->router->generate('subugoe_iiif_annotation-list', ['id' => $document->getId(), 'name' => $canvasId], UrlGeneratorInterface::ABSOLUTE_URL))
                ->setType('sc:AnnotationList');

            $canvas->setOtherContent([$annotationList]);
        }

        if ($physicalStructure->getFilename()) {
            $dimensions = $this->getImageDimenstions($physicalStructure->getFilename());
            /*
             * @see https://www.php.net/manual/de/function.getimagesize.php#refsect1-function.getimagesize-returnvalues
             * 0 = width
             * 1 = height
             */
            $canvas
                ->setWidth($dimensions[0])
                ->setHeight($dimensions[1]);
        } else {
            $canvas
                ->setHeight(self::DEFAULT_HEIGHT)
                ->setWidth(self::DEFAULT_WIDTH);
        }

        return $canvas;
    }

    /**
     * @throws IIIFException
     */
    public function getImage(\Subugoe\IIIFModel\Model\Document $document, string $imageId): GenericResource
    {
        $imageParameters = [
            'identifier' => $imageId,
            'region' => 'full',
            'size' => 'full',
            'rotation' => 0,
            'quality' => 'default',
            'format' => $document->getImageFormat(),
        ];
        $imageFileName = $this->getFilenameFromImageId($document, $imageId);

        $image = new GenericResource();
        $resource = new ResourceData();
        $mimes = new MimeTypes();

        $format = $mimes->getMimeType($document->getImageFormat()) ?: '';

        $this->router->setContext($this->setRoutingContext(self::CONTEXT_IMAGE));

        $imageService = new Service();
        $imageService
            ->setId($this->router->generate(
                'subugoe_iiif_image_base', ['identifier' => $imageParameters['identifier']], UrlGeneratorInterface::ABSOLUTE_URL));
        $this->router->setContext($this->setRoutingContext(self::CONTEXT_MANIFESTS));

        $resource
            ->setId($this->router->generate('subugoe_iiif_image_base', ['identifier' => $imageParameters['identifier']], UrlGeneratorInterface::ABSOLUTE_URL))
            ->setFormat($format)
            ->setService($imageService);

        if ('' !== $imageFileName) {
            $dimensions = $this->getImageDimenstions($imageFileName);

            $resource
                ->setWidth($dimensions[0])
                ->setHeight($dimensions[1]);
        } else {
            $resource
            ->setWidth(self::DEFAULT_WIDTH)
            ->setHeight(self::DEFAULT_HEIGHT);
        }

        $image
            ->setId($this->router->generate('subugoe_iiif_imagepresentation', ['id' => $document->getId(), 'name' => $imageId], UrlGeneratorInterface::ABSOLUTE_URL))
            ->setResource($resource)
            ->setOn($this->router->generate('subugoe_iiif_canvas', ['id' => $document->getId(), 'canvas' => $imageId], UrlGeneratorInterface::ABSOLUTE_URL));

        return $image;
    }

    /**
     * @throws IIIFException
     */
    public function getSequence(\Subugoe\IIIFModel\Model\Document $document, string $name): Sequence
    {
        $this->router->setContext($this->setRoutingContext(self::CONTEXT_MANIFESTS));

        $canvases = $this->getCanvases($document);

        $sequence = new Sequence();
        $sequence
            ->setId($this->router->generate('subugoe_iiif_sequence', [
                'id' => $document->getId(),
                'name' => $name,
            ],
                UrlGeneratorInterface::ABSOLUTE_URL))
            ->setCanvases($canvases)
            ->setStartCanvas($this->router->generate('subugoe_iiif_canvas', [
                'id' => $document->getId(),
                'canvas' => $document->getPhysicalStructure(0)->getIdentifier(),
            ],
                UrlGeneratorInterface::ABSOLUTE_URL));

        return $sequence;
    }

    private function getImageDimenstions(string $filename): array
    {
        $image = $this->fileService->getSourceFilesystem()->read($filename);
        $dimensions = getimagesizefromstring($image);
        /*
         * @see https://www.php.net/manual/de/function.getimagesize.php#refsect1-function.getimagesize-returnvalues
         * 0 = width
         * 1 = height
         */

        return [$dimensions[0], $dimensions[1]];
    }

    private function getFilenameFromImageId(
            \Subugoe\IIIFModel\Model\Document $document, string $imageId): string
    {
        /** @var PhysicalStructure $physicalStructure */
        foreach ($document->getPhysicalStructures() as $physicalStructure) {
            if ($imageId === $physicalStructure->getIdentifier()) {
                return $physicalStructure->getFilename();
            }
        }

        return '';
    }

    /**
     * @throws IIIFException
     */
    private function setRoutingContext(string $type): RequestContext
    {
        if ($type !== static::CONTEXT_MANIFESTS && $type !== static::CONTEXT_IMAGE) {
            throw new IIIFException(sprintf('Request type %s not defined', $type), 1_497_438_291);
        }

        if (array_key_exists('host', $this->imageConfiguration['http']) && $type === static::CONTEXT_IMAGE) {
            $context = new RequestContext();

            $url = sprintf('%s://%s', $this->imageConfiguration['http']['scheme'], $this->imageConfiguration['http']['host']);
            $urlParts = parse_url($url);

            if (isset($urlParts['port'])) {
                $context->setHttpPort($urlParts['port']);
            }

            $context
                ->setHost($urlParts['host'])
                ->setScheme($urlParts['scheme']);

            return $context;
        }

        if (array_key_exists('host', $this->presentationConfiguration['http']) && $type === static::CONTEXT_MANIFESTS) {
            $context = new RequestContext();

            $url = sprintf('%s://%s', $this->presentationConfiguration['http']['scheme'], $this->presentationConfiguration['http']['host']);
            $urlParts = parse_url($url);

            if (isset($urlParts['port'])) {
                $context->setHttpPort($urlParts['port']);
            }

            $context->setHost($urlParts['host']);
            $context->setScheme($urlParts['scheme']);

            return $context;
        }

        return $this->router->getContext();
    }

    private function getPagePositionByIdentifier(\Subugoe\IIIFModel\Model\Document $document, string $identifier): int
    {
        $position = 0;
        /** @var PhysicalStructure $physicalStructure */
        foreach ($document->getPhysicalStructures() as $physicalStructure) {
            if ($physicalStructure->getIdentifier() === $identifier) {
                return $position;
            }
            ++$position;
        }

        throw new InvalidArgumentException(sprintf('Page with label %s not found in document %s', $identifier, $document->getId()), 1_490_689_215);
    }

    /**
     * @throws IIIFException
     */
    private function getMembersForRange(\Subugoe\IIIFModel\Model\Document $document, string $range): array
    {
        $logicalStructures = $document->getLogicalStructures();
        $numberOflogicalStructures = count($logicalStructures);
        $i = 0;

        while ($i <= $numberOflogicalStructures) {
            /** @var LogicalStructure $logicalStructure */
            $logicalStructure = $logicalStructures[$i];

            if ($logicalStructure->getId() === $range) {
                return $this->getMembersOfLogicalStructure($document, $logicalStructure);
            }
            ++$i;
        }

        return [];
    }

    /**
     * @return \DateTime|\DateTimeImmutable
     */
    private function getNavDate(\Subugoe\IIIFModel\Model\Document $document): \DateTimeInterface
    {
        return DateTime::createFromFormat('Y-m-d H:i:s', vsprintf('%d-%s-%s %s:%s:%s',
            [
                $document->getPublishingYear(),
                '01',
                '01',
                '00',
                '00',
                '00',
            ]
        ));
    }

    /**
     * @throws IIIFException
     */
    private function getThumbnail(\Subugoe\IIIFModel\Model\Document $document): Image
    {
        $this->router->setContext($this->setRoutingContext(self::CONTEXT_IMAGE));

        $thumbnail = new Image();
        $thumbnailService = new Service();
        $thumbnailService->setId($this->router->generate('subugoe_iiif_manifest', ['id' => $document->getId()], UrlGeneratorInterface::ABSOLUTE_URL));

        $thumbnailParameters = [
            'identifier' => $document->getPhysicalStructure(0)->getIdentifier(),
            'region' => 'full',
            'size' => $this->imageConfiguration['thumbnail_size'],
            'rotation' => 0,
            'quality' => 'default',
            'format' => 'jpg',
        ];

        $thumbnail->setId($this->router->generate(
            'subugoe_iiif_image',
            $thumbnailParameters,
            UrlGeneratorInterface::ABSOLUTE_URL
            )
        );
        $thumbnail->setService($thumbnailService);

        return $thumbnail;
    }

    private function getLogo(): Image
    {
        $logo = new Image();
        $logoService = new Service();

        $logoService->setId($this->presentationConfiguration['service_id']);
        $logo->setId($this->presentationConfiguration['logo']);

        $logo->setService($logoService);

        return $logo;
    }

    private function getMetadata(\Subugoe\IIIFModel\Model\Document $document): array
    {
        $metadata = [];
        foreach ($document->getMetadata() as $key => $value) {
            if (!empty($value)) {
                $data = new Metadata();
                $data
                    ->setLabel((string) $key)
                    ->setValue($value);
                $metadata[] = $data;
            }
        }

        return $metadata;
    }

    private function getStructureMetadata(LogicalStructure $structure): array
    {
        $metadata = [];
        foreach ($structure->getMetadata() as $key => $value) {
            if (!empty($value)) {
                $data = new Metadata();
                $data
                    ->setLabel($key)
                    ->setValue($value);
                $metadata[] = $data;
            }
        }

        return $metadata;
    }

    private function getAttribution(\Subugoe\IIIFModel\Model\Document $document): string
    {
        if (array_key_exists('0', $document->getRightsOwner())) {
            return $document->getRightsOwner()[0];
        }

        return '';
    }

    /**
     * @throws IIIFException
     * @throws Exception
     */
    private function getStructures(\Subugoe\IIIFModel\Model\Document $document): array
    {
        $this->router->setContext($this->setRoutingContext(self::CONTEXT_MANIFESTS));

        $structures = [];
        $numberOfStructureElements = count($document->getLogicalStructures());

        $counter = 0;
        $counterEnd = $numberOfStructureElements - 1;

        if ($numberOfStructureElements > 0) {
            $firstLevel = $document->getLogicalStructure(0)->getLevel();
            while ($counter <= $counterEnd) {
                $logicalStructure = $document->getLogicalStructure($counter);
                $canvases = $this->getMembersOfLogicalStructure($document, $logicalStructure);

                $structure = new Structure();
                $structure
                    ->setId($this->router->generate('subugoe_iiif_range', [
                        'id' => $document->getId(),
                        'range' => $logicalStructure->getId(),
                    ], UrlGeneratorInterface::ABSOLUTE_URL)
                    )
                    ->setLabel($logicalStructure->getLabel())
                    ->setType('sc:Canvas')
                    ->setRendering($logicalStructure->getRenderings())
                    ->setCanvases($canvases)
                    ->setMetadata($this->getStructureMetadata($logicalStructure));

                if ($firstLevel !== $logicalStructure->getLevel()) {
                    $parentStructure = $this->getPreviousHierarchyStructure($document, $logicalStructure, $counter);
                    $structure->setWithin($this->router->generate('subugoe_iiif_range', [
                        'id' => $document->getId(),
                        'range' => $parentStructure->getId(),
                    ], UrlGeneratorInterface::ABSOLUTE_URL));
                }
                $structures[] = $structure;

                ++$counter;
            }
        }

        return $structures;
    }

    /**
     * @throws Exception
     */
    private function getPreviousHierarchyStructure(\Subugoe\IIIFModel\Model\Document $document, LogicalStructure $structure, int $position): LogicalStructure
    {
        $level = $structure->getLevel();
        $parentLevel = $level - 1;

        for ($i = $position; $i >= 0; --$i) {
            if ($document->getLogicalStructure($i)->getLevel() === $parentLevel) {
                return $document->getLogicalStructure($i);
            }
        }

        throw new DataException(vsprintf('Parent structure at position %d with level %d and parent level %d not defined', [$position, $level, $parentLevel]), 1_494_506_264);
    }

    /**
     * @throws IIIFException
     */
    private function getMembersOfLogicalStructure(\Subugoe\IIIFModel\Model\Document $document, LogicalStructure $logicalStructure): array
    {
        $this->router->setContext($this->setRoutingContext(self::CONTEXT_MANIFESTS));

        $structureStart = $this->getPositionOfPhysicalPage($document, $logicalStructure->getStartPage());
        $structureEnd = $this->getPositionOfPhysicalPage($document, $logicalStructure->getEndPage());
        $numberOfElements = ($structureEnd - $structureStart + 1);

        $canvases = [];

        for ($i = 0; $i < $numberOfElements; ++$i) {
            $canvases[] = $this->router->generate('subugoe_iiif_canvas', [
                'id' => $document->getId(),
                'canvas' => $document->getPhysicalStructure($structureStart + $i)->getIdentifier(),
            ], UrlGeneratorInterface::ABSOLUTE_URL);
        }

        return $canvases;
    }

    /**
     * @throws MalformedDocumentException
     */
    private function getPositionOfPhysicalPage(\Subugoe\IIIFModel\Model\Document $document, int $page): int
    {
        $i = 0;
        /** @var PhysicalStructure $structure */
        foreach ($document->getPhysicalStructures() as $structure) {
            if ($structure->getOrder() === $page) {
                return $i;
            }
            ++$i;
        }
        // PPN617021074
        throw new MalformedDocumentException(vsprintf('Document %s may contain an invalid or inconsistent structure. Page %d not found in %d iterations.', [$document->getId(), $page, $i]));
    }

    /**
     * @throws IIIFException
     */
    private function getSequences(\Subugoe\IIIFModel\Model\Document $document): Sequence
    {
        $this->router->setContext($this->setRoutingContext(self::CONTEXT_MANIFESTS));

        $canvases = $this->getCanvases($document);

        $sequences = new Sequence();
        $sequences
            ->setId($this->router->generate('subugoe_iiif_sequence', [
                'id' => $document->getId(),
                'name' => 'normal',
            ],
                UrlGeneratorInterface::ABSOLUTE_URL))
            ->setContext('')
            ->setCanvases($canvases)
            ->setStartCanvas($this->router->generate('subugoe_iiif_canvas', [
                'id' => $document->getId(),
                'canvas' => $document->getPhysicalStructure(0)->getIdentifier(),
            ],
                UrlGeneratorInterface::ABSOLUTE_URL));

        return $sequences;
    }

    private function getLabelForCanvas(\Subugoe\IIIFModel\Model\Document $document, string $canvasId): string
    {
        $physicalStructures = $document->getPhysicalStructures();

        /** @var PhysicalStructure $physicalStructure */
        foreach ($physicalStructures as $physicalStructure) {
            if ($physicalStructure->getIdentifier() === $canvasId) {
                return $physicalStructure->getLabel();
            }
        }

        return '';
    }

    private function getLabelForRange(\Subugoe\IIIFModel\Model\Document $document, string $rangeId): string
    {
        $logicalStructures = $document->getLogicalStructures();

        /** @var LogicalStructure $logicalStructure */
        foreach ($logicalStructures as $logicalStructure) {
            if ($logicalStructure->getId() === $rangeId) {
                return $logicalStructure->getLabel();
            }
        }

        return '';
    }

    /**
     * @throws IIIFException
     */
    private function getImages(\Subugoe\IIIFModel\Model\Document $document, string $canvasId): array
    {
        return [$this->getImage($document, $canvasId)];
    }

    private function getCanvases(\Subugoe\IIIFModel\Model\Document $document): array
    {
        $canvases = [];
        $numberOfPages = count($document->getPhysicalStructures());

        $count = 0;

        while ($count < $numberOfPages) {
            $canvas = $this->getCanvas($document, $document->getPhysicalStructure($count)->getIdentifier(), $count);
            // Embedded canvases do not have a context field
            $canvas->setContext('');
            $canvases[] = $canvas;
            ++$count;
        }

        return $canvases;
    }
}
