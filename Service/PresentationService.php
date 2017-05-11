<?php

declare(strict_types=1);

namespace Subugoe\IIIFBundle\Service;

use Subugoe\IIIFBundle\Exception\DataException;
use Subugoe\IIIFBundle\Exception\MalformedDocumentException;
use Subugoe\IIIFBundle\Model\LogicalStructure;
use Subugoe\IIIFBundle\Model\PhysicalStructure;
use Subugoe\IIIFBundle\Model\Presentation\AnnotationList;
use Subugoe\IIIFBundle\Model\Presentation\Canvas;
use Subugoe\IIIFBundle\Model\Presentation\Collection;
use Subugoe\IIIFBundle\Model\Presentation\Collections;
use Subugoe\IIIFBundle\Model\Presentation\Document;
use Subugoe\IIIFBundle\Model\Presentation\Image;
use Subugoe\IIIFBundle\Model\Presentation\GenericResource;
use Subugoe\IIIFBundle\Model\Presentation\Metadata;
use Subugoe\IIIFBundle\Model\Presentation\Range;
use Subugoe\IIIFBundle\Model\Presentation\ResourceData;
use Subugoe\IIIFBundle\Model\Presentation\Sequence;
use Subugoe\IIIFBundle\Model\Presentation\Service;
use Subugoe\IIIFBundle\Model\Presentation\Structure;
use Symfony\Component\Routing\RouterInterface;

class PresentationService
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var array
     */
    private $imageConfiguration;

    /**
     * @var array
     */
    private $presentationConfiguration;

    /**
     * PresentationService constructor.
     *
     * @param RouterInterface $router
     * @param array           $imageConfiguration
     * @param array           $presentationConfiguration
     */
    public function __construct(RouterInterface $router, array $imageConfiguration, array $presentationConfiguration)
    {
        $this->router = $router;
        $this->imageConfiguration = $imageConfiguration;
        $this->presentationConfiguration = $presentationConfiguration;
    }

    /**
     * @param \Subugoe\IIIFBundle\Model\Document $document
     *
     * @return Document
     */
    public function getManifest(\Subugoe\IIIFBundle\Model\Document $document): Document
    {
        $manifest = new Document();

        $metadata = $this->getMetadata($document);
        $thumbnail = $this->getThumbnail($document);
        $logo = $this->getLogo();
        $sequences = $this->getSequences($document, 'normal');
        $structures = $this->getStructures($document);
        $attribution = $this->getAttribution($document);

        $manifest
            ->setId($this->router->generate(
                '_detail',
                [
                    'id' => $document->getId(),
                ],
                RouterInterface::NETWORK_PATH)
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
            ->setRendering($document->getRenderings());

        if (!empty($document->getDescription())) {
            $manifest->setDescription($document->getDescription());
        }

        foreach ($document->getClassification() as $classification) {
            $manifest->setWithin($this->router->generate('subugoe_iiif_collection', ['id' => $classification], RouterInterface::NETWORK_PATH));
        }

        return $manifest;
    }

    /**
     * @param \Subugoe\IIIFBundle\Model\Document $document
     * @param string                             $name
     *
     * @return AnnotationList
     */
    public function getAnnotationList(\Subugoe\IIIFBundle\Model\Document $document, string $name): AnnotationList
    {
        $annotationList = new AnnotationList();
        $annotationList->setId($this->router->generate('subugoe_iiif_annotation-list', [
            'id' => $document->getId(),
            'name' => $name,
        ], RouterInterface::NETWORK_PATH));

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

    /**
     * @param Collection $collection
     *
     * @return Collection
     */
    public function getCollection(Collection $collection)
    {
        return $collection;
    }

    /**
     * @param Collections $collections
     *
     * @return Collections
     */
    public function getCollections(Collections $collections)
    {
        return $collections;
    }

    /**
     * @param \Subugoe\IIIFBundle\Model\Document $document
     * @param string                             $name
     *
     * @return Range
     */
    public function getRange(\Subugoe\IIIFBundle\Model\Document $document, string $name)
    {
        $range = new Range();

        $range
            ->setId($this->router->generate('subugoe_iiif_range', ['id' => $document->getId(), 'range' => $name], RouterInterface::NETWORK_PATH))
            ->setLabel($this->getLabelForRange($document, $name))
            ->setMembers($this->getMembersForRange($document, $name));

        return $range;
    }

    /**
     * @param \Subugoe\IIIFBundle\Model\Document $document
     * @param string                             $canvasId
     * @param int                                $physicalStructureId
     *
     * @return Canvas
     */
    public function getCanvas(\Subugoe\IIIFBundle\Model\Document $document, string $canvasId, int $physicalStructureId = -1): Canvas
    {
        $images = $this->getImages($document, $canvasId);

        if ($physicalStructureId !== -1) {
            $label = $document->getPhysicalStructure($physicalStructureId)->getLabel();
        } else {
            $label = $this->getLabelForCanvas($document, $canvasId);
        }

        $canvas = new Canvas();
        $canvas
            ->setId($this->router->generate('subugoe_iiif_canvas', [
                'id' => $document->getId(),
                'canvas' => $canvasId,
            ],
                RouterInterface::NETWORK_PATH))
            ->setLabel($label)
            ->setHeight(400)
            ->setWidth(300)
            ->setImages($images);

        if (!empty($document->getPhysicalStructure($physicalStructureId)->getAnnotation())) {
            $annotationList = new AnnotationList();
            $annotationList
                ->setId($this->router->generate('subugoe_iiif_annotation-list', ['id' => $document->getId(), 'name' => $canvasId], RouterInterface::NETWORK_PATH))
                ->setType('sc:AnnotationList');

            $canvas->setOtherContent([$annotationList]);
        }

        return $canvas;
    }

    /**
     * @param \Subugoe\IIIFBundle\Model\Document $document
     * @param string                             $imageId
     *
     * @return GenericResource
     */
    public function getImage(\Subugoe\IIIFBundle\Model\Document $document, string $imageId): GenericResource
    {
        $imageParameters = [
            'identifier' => $imageId,
            'region' => 'full',
            'size' => 'full',
            'rotation' => 0,
            'quality' => 'default',
            'format' => $document->getImageFormat(),
        ];

        $image = new GenericResource();
        $resource = new ResourceData();
        $mimes = new \Mimey\MimeTypes();

        $format = $mimes->getMimeType($document->getImageFormat()) ?: '';

        $imageService = new Service();
        $imageService
            ->setId($this->router->generate(
                'subugoe_iiif_image_base', ['identifier' => $imageParameters['identifier']], RouterInterface::NETWORK_PATH));

        $resource
            ->setId($this->router->generate('subugoe_iiif_image_base', ['identifier' => $imageParameters['identifier']], RouterInterface::NETWORK_PATH))
            ->setFormat($format)
            ->setWidth(300)
            ->setHeight(400)
            ->setService($imageService);

        $image
            ->setId($this->router->generate('subugoe_iiif_imagepresentation', ['id' => $document->getId(), 'name' => $imageId], RouterInterface::NETWORK_PATH))
            ->setResource($resource);

        return $image;
    }

    /**
     * @param \Subugoe\IIIFBundle\Model\Document $document
     * @param string                             $name
     *
     * @return Sequence
     */
    public function getSequence(\Subugoe\IIIFBundle\Model\Document $document, $name): Sequence
    {
        $canvases = $this->getCanvases($document);

        $sequence = new Sequence();
        $sequence
            ->setId($this->router->generate('subugoe_iiif_sequence', [
                'id' => $document->getId(),
                'name' => $name,
            ],
                RouterInterface::NETWORK_PATH))
            ->setCanvases($canvases)
            ->setStartCanvas($this->router->generate('subugoe_iiif_canvas', [
                'id' => $document->getId(),
                'canvas' => $document->getPhysicalStructure(0)->getPage(),
            ],
                RouterInterface::NETWORK_PATH));

        return $sequence;
    }

    /**
     * @param \Subugoe\IIIFBundle\Model\Document $document
     * @param string                             $identifier
     *
     * @return int
     */
    private function getPagePositionByIdentifier(\Subugoe\IIIFBundle\Model\Document $document, string $identifier): int
    {
        $position = 0;
        /** @var PhysicalStructure $physicalStructure */
        foreach ($document->getPhysicalStructures() as $physicalStructure) {
            if ($physicalStructure->getIdentifier() === $identifier) {
                return $position;
            }
            ++$position;
        }

        throw new \InvalidArgumentException(sprintf('Page with label %s not found in document %s', $identifier, $document->getId()), 1490689215);
    }

    /**
     * @param \Subugoe\IIIFBundle\Model\Document $document
     * @param string                             $range
     *
     * @return array
     */
    private function getMembersForRange(\Subugoe\IIIFBundle\Model\Document $document, string $range)
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
     * @param \Subugoe\IIIFBundle\Model\Document $document
     *
     * @return \DateTime
     */
    private function getNavDate(\Subugoe\IIIFBundle\Model\Document $document)
    {
        return \DateTime::createFromFormat('Y-m-d H:i:s', vsprintf('%d-%s-%s %s:%s:%s',
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
     * @param \Subugoe\IIIFBundle\Model\Document $document
     *
     * @return Image
     */
    private function getThumbnail(\Subugoe\IIIFBundle\Model\Document $document): Image
    {
        $thumbnail = new Image();
        $thumbnailService = new Service();

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
            RouterInterface::NETWORK_PATH
            )
        );
        $thumbnail->setService($thumbnailService);

        return $thumbnail;
    }

    /**
     * @return Image
     */
    private function getLogo(): Image
    {
        $logo = new Image();
        $logoService = new Service();

        $logoService->setId($this->presentationConfiguration['service_id']);
        $logo->setId($this->presentationConfiguration['logo']);

        $logo->setService($logoService);

        return $logo;
    }

    /**
     * @param \Subugoe\IIIFBundle\Model\Document $document
     *
     * @return array
     */
    private function getMetadata(\Subugoe\IIIFBundle\Model\Document $document): array
    {
        $metadata = [
            'author' => $document->getAuthors(),
            'publishing_place' => $document->getPublishingPlaces(),
            'classification' => $document->getClassification(),
            'publishing_year' => (string) $document->getPublishingYear(),
            'publisher' => $document->getPublisher(),
            'language' => $document->getLanguage(),
            'subtitle' => $document->getSubtitle(),
        ];

        $metadata = array_merge($metadata, $document->getMetadata());

        $md = [];
        foreach ($metadata as $key => $value) {
            if (!empty($value)) {
                $data = new Metadata();
                $data
                    ->setLabel($key)
                    ->setValue($value);
                $md[] = $data;
            }
        }

        return $md;
    }

    /**
     * @param \Subugoe\IIIFBundle\Model\Document $document
     *
     * @return string
     */
    private function getAttribution(\Subugoe\IIIFBundle\Model\Document $document): string
    {
        if (array_key_exists('0', $document->getRightsOwner())) {
            return $document->getRightsOwner()[0];
        }

        return '';
    }

    /**
     * @param \Subugoe\IIIFBundle\Model\Document $document
     *
     * @return array
     */
    private function getStructures(\Subugoe\IIIFBundle\Model\Document $document): array
    {
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
                    ], RouterInterface::NETWORK_PATH)
                    )
                    ->setLabel($logicalStructure->getLabel())
                    ->setType('sc:Canvas')
                    ->setCanvases($canvases);

                if ($firstLevel !== $logicalStructure->getLevel()) {
                    $parentStructure = $this->getPreviousHierarchyStructure($document, $logicalStructure, $counter);
                    $structure->setWithin($this->router->generate('subugoe_iiif_range', [
                        'id' => $document->getId(),
                        'range' => $parentStructure->getId(),
                    ], RouterInterface::NETWORK_PATH));
                }
                $structures[] = $structure;

                ++$counter;
            }
        }

        return $structures;
    }

    /**
     * @param \Subugoe\IIIFBundle\Model\Document $document
     * @param LogicalStructure                   $structure
     * @param int                                $position
     *
     * @throws \Exception
     *
     * @return LogicalStructure
     */
    private function getPreviousHierarchyStructure(\Subugoe\IIIFBundle\Model\Document $document, LogicalStructure $structure, int $position): LogicalStructure
    {
        $level = $structure->getLevel();
        $parentLevel = $level - 1;

        for ($i = $position; $i >= 1; --$i) {
            if ($document->getLogicalStructure($i)->getLevel() === $parentLevel) {
                return $document->getLogicalStructure($i);
            }
        }

        throw new DataException(vsprintf(
            'Parent structure at position %d with level %d and parent level %d not defined', [
                $position, $level, $parentLevel,
            ]
        ), 1494506264);
    }

    /**
     * @param \Subugoe\IIIFBundle\Model\Document $document
     * @param LogicalStructure                   $logicalStructure
     *
     * @return array
     */
    private function getMembersOfLogicalStructure(\Subugoe\IIIFBundle\Model\Document $document, LogicalStructure $logicalStructure)
    {
        $structureStart = $this->getPositionOfPhysicalPage($document, $logicalStructure->getStartPage());
        $structureEnd = $this->getPositionOfPhysicalPage($document, $logicalStructure->getEndPage());
        $numberOfElements = ($structureEnd - $structureStart + 1);

        $canvases = [];

        for ($i = 0; $i < $numberOfElements; ++$i) {
            $canvases[] = $this->router->generate('subugoe_iiif_canvas', [
                'id' => $document->getId(),
                'canvas' => $document->getPhysicalStructure($structureStart + $i)->getIdentifier(),
            ], RouterInterface::NETWORK_PATH);
        }

        return $canvases;
    }

    /**
     * @param \Subugoe\IIIFBundle\Model\Document $document
     * @param int                                $page
     *
     * @throws MalformedDocumentException
     *
     * @return int
     */
    private function getPositionOfPhysicalPage(\Subugoe\IIIFBundle\Model\Document $document, int $page)
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
        throw new MalformedDocumentException(vsprintf('Document %s may contain an invalid or inconsistent structure. Page %d not found in %d iterations.', [
            $document->getId(),
            $page,
            $i,
        ]));
    }

    /**
     * @param \Subugoe\IIIFBundle\Model\Document $document
     * @param string                             $name
     *
     * @return Sequence
     */
    private function getSequences(\Subugoe\IIIFBundle\Model\Document $document, string $name): Sequence
    {
        $canvases = $this->getCanvases($document);

        $sequences = new Sequence();
        $sequences
            ->setId($this->router->generate('subugoe_iiif_sequence', [
                'id' => $document->getId(),
                'name' => $name,
            ],
                RouterInterface::NETWORK_PATH))
            ->setCanvases($canvases)
            ->setStartCanvas($this->router->generate('subugoe_iiif_canvas', [
                'id' => $document->getId(),
                'canvas' => $document->getPhysicalStructure(0)->getPage(),
            ],
                RouterInterface::NETWORK_PATH));

        return $sequences;
    }

    /**
     * @param \Subugoe\IIIFBundle\Model\Document $document
     * @param string                             $canvasId
     *
     * @return string
     */
    private function getLabelForCanvas(\Subugoe\IIIFBundle\Model\Document $document, string $canvasId)
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

    /**
     * @param \Subugoe\IIIFBundle\Model\Document $document
     * @param string                             $rangeId
     *
     * @return string
     */
    private function getLabelForRange(\Subugoe\IIIFBundle\Model\Document $document, string $rangeId)
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
     * @param \Subugoe\IIIFBundle\Model\Document $document Document ID
     * @param string                             $canvasId
     *
     * @return array
     */
    private function getImages(\Subugoe\IIIFBundle\Model\Document $document, string $canvasId): array
    {
        return [$this->getImage($document, $canvasId)];
    }

    /**
     * @param \Subugoe\IIIFBundle\Model\Document $document
     *
     * @return array
     */
    private function getCanvases(\Subugoe\IIIFBundle\Model\Document $document): array
    {
        $canvases = [];
        $numberOfPages = count($document->getPhysicalStructures());

        $count = 0;

        while ($count < $numberOfPages) {
            $canvases[] = $this->getCanvas($document, $document->getPhysicalStructure($count)->getIdentifier(), $count);
            ++$count;
        }

        return $canvases;
    }
}
