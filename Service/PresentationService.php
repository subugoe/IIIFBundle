<?php

declare(strict_types=1);

namespace Subugoe\IIIFBundle\Service;

use Subugoe\IIIFBundle\Model\PhysicalStructure;
use Subugoe\IIIFBundle\Model\Presentation\Canvas;
use Subugoe\IIIFBundle\Model\Presentation\Document;
use Subugoe\IIIFBundle\Model\Presentation\Image;
use Subugoe\IIIFBundle\Model\Presentation\ImageResource;
use Subugoe\IIIFBundle\Model\Presentation\Metadata;
use Subugoe\IIIFBundle\Model\Presentation\ResourceData;
use Subugoe\IIIFBundle\Model\Presentation\Sequence;
use Subugoe\IIIFBundle\Model\Presentation\Service;
use Subugoe\IIIFBundle\Model\Presentation\Structure;
use Subugoe\IIIFBundle\Translator\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class PresentationService
{
    /**
     * @var Router
     */
    private $router;

    /**
     * @var TranslatorInterface
     */
    private $translator;

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
     * @param Router              $router
     * @param TranslatorInterface $translator
     * @param array               $imageConfiguration
     * @param array               $presentationConfiguration
     */
    public function __construct(Router $router, TranslatorInterface $translator, array $imageConfiguration, array $presentationConfiguration)
    {
        $this->router = $router;
        $this->translator = $translator;
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
                Router::ABSOLUTE_URL)
            )
            ->setLabel($document->getTitle()[0])
            ->setNavDate($this->getNavDate($document))
            ->setThumbnail($thumbnail)
            ->setMetadata($metadata)
            ->setAttribution($attribution)
            ->setLogo($logo)
            ->setSequences([$sequences])
            ->setStructures($structures);

        if (!empty($document->getDescription())) {
            $manifest->setDescription($document->getDescription());
        }

        foreach ($document->getClassification() as $classification) {
            $manifest->setWithin($this->router->generate('subugoe_iiif_collection', ['id' => $classification], Router::ABSOLUTE_URL));
        }

        return $manifest;
    }

    /**
     * @param string $id
     *
     * @return string
     */
    public function getCollection(string $id)
    {
        return 'Not implemented yet';
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
            Router::ABSOLUTE_URL
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

        if ($numberOfStructureElements > 0) {
            $numberOfStructureElements = --$numberOfStructureElements;

            $levelOfFirstStructure = $document->getLogicalStructure(0)->getLevel();

            for ($i = 0; $i < $numberOfStructureElements; ++$i) {
                $logicalStructure = $document->getLogicalStructure($i);
                if ($levelOfFirstStructure === $logicalStructure->getLevel()) {
                    $structureStart = $logicalStructure->getStartPage();
                    $structureEnd = $logicalStructure->getEndPage();

                    $canvases = [];
                    for ($j = $structureStart; $j <= $structureEnd; ++$j) {
                        $canvases[] = $this->router->generate('subugoe_iiif_canvas', [
                            'id' => $document->getId(),
                            'canvas' => $document->getPhysicalStructure($j - 1)->getIdentifier(),
                        ], Router::ABSOLUTE_URL);
                    }

                    $structure = new Structure();
                    $structure
                        ->setId($this->router->generate('subugoe_iiif_range', [
                            'id' => $document->getId(),
                            'range' => $logicalStructure->getId(),
                        ], Router::ABSOLUTE_URL)
                        )
                        ->setLabel($logicalStructure->getLabel())
                        ->setType('sc:Canvas')
                        ->setCanvases($canvases);

                    $structures[] = $structure;
                }
            }
        }

        return $structures;
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
                Router::ABSOLUTE_URL))
            ->setCanvases($canvases)
            ->setStartCanvas($this->router->generate('subugoe_iiif_canvas', [
                'id' => $document->getId(),
                'canvas' => $document->getPhysicalStructure(0)->getPage(),
            ],
                Router::ABSOLUTE_URL));

        return $sequences;
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
                Router::ABSOLUTE_URL))
            ->setLabel($label)
            ->setHeight(400)
            ->setWidth(300)
            ->setImages($images);

        return $canvas;
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
     * @param string                             $imageId
     *
     * @return ImageResource
     */
    public function getImage(\Subugoe\IIIFBundle\Model\Document $document, string $imageId): ImageResource
    {
        $imageParameters = [
            'identifier' => $imageId,
            'region' => 'full',
            'size' => 'full',
            'rotation' => 0,
            'quality' => 'default',
            'format' => $document->getImageFormat(),
        ];

        $image = new ImageResource();
        $resource = new ResourceData();
        $mimes = new \Mimey\MimeTypes();

        $format = $mimes->getMimeType($document->getImageFormat()) ?: '';

        $imageService = new Service();
        $imageService
            ->setId($this->router->generate(
                'subugoe_iiif_image_base', ['identifier' => $imageParameters['identifier']], Router::ABSOLUTE_URL));

        $resource
            ->setId($this->router->generate('subugoe_iiif_image_base', ['identifier' => $imageParameters['identifier']], Router::ABSOLUTE_URL))
            ->setFormat($format)
            ->setWidth(300)
            ->setHeight(400)
            ->setService($imageService);

        $image
            ->setId($this->router->generate('subugoe_iiif_imagepresentation', ['id' => $document->getId(), 'name' => $imageId], Router::ABSOLUTE_URL))
            ->setResource($resource);

        return $image;
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

        for ($i = 0; $i < $numberOfPages; ++$i) {
            $canvases[] = $this->getCanvas($document, $document->getPhysicalStructure($i)->getIdentifier(), $i);
        }

        return $canvases;
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
                Router::ABSOLUTE_URL))
            ->setCanvases($canvases)
            ->setStartCanvas($this->router->generate('subugoe_iiif_canvas', [
                'id' => $document->getId(),
                'canvas' => $document->getPhysicalStructure(0)->getPage(),
            ],
                Router::ABSOLUTE_URL));

        return $sequence;
    }
}
