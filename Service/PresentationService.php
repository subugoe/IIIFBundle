<?php

namespace Subugoe\IIIFBundle\Service;

use Subugoe\IIIFBundle\Model\Presentation\Canvas;
use Subugoe\IIIFBundle\Model\Presentation\Document;
use Subugoe\IIIFBundle\Model\Presentation\Image;
use Subugoe\IIIFBundle\Model\Presentation\ImageResource;
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
     * @var string
     */
    private $thumbnailSize;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * PresentationService constructor.
     *
     * @param Router              $router
     * @param TranslatorInterface $translator
     * @param string              $thumbnailSize
     */
    public function __construct(Router $router, TranslatorInterface $translator, string $thumbnailSize)
    {
        $this->router = $router;
        $this->thumbnailSize = $thumbnailSize;
        $this->translator = $translator;
    }

    /**
     * @param string $id
     *
     * @return Document
     */
    public function getManifest(string $id): Document
    {
        $document = $this->translator->getDocumentById($id);

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
            ->setThumbnail($thumbnail)
            ->setMetadata($metadata)
            ->setAttribution($attribution)
            ->setLogo($logo)
            ->setSequences([$sequences])
            ->setStructures($structures)
        ;

        return $manifest;
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
            'identifier' => vsprintf('%s:%s', [$document->getId(), $document->getPages()[0]]),
            'region' => 'full',
            'size' => $this->thumbnailSize,
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

        $logoService->setId('http://gdzdev.sub.uni-goettingen.de/');
        $logo->setId('http://gdz.sub.uni-goettingen.de/fileadmin/gdz/layout/head_logo.jpg');

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
            'publishing_year' => $document->getPublishingYear(),
            'publisher' => $document->getPublisher(),
            'language' => $document->getLanguage(),
            'subtitle' => $document->getSubtitle(),
        ];

        $metadata = array_filter(
            $metadata,
            function($value) {
                if (!empty($value)) {
                    return true;
                }
                return false;
            });

        return $metadata;
    }

    /**
     * @param \Subugoe\IIIFBundle\Model\Document $document
     *
     * @return string
     */
    private function getAttribution(\Subugoe\IIIFBundle\Model\Document $document): string
    {
        if (array_key_exists('0', $document->getRightsOwner())) {
            return $document->getRightsOwner();
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
        $numberOfStructureElements = count($document->getLogicalIds()) - 1;

        for ($i = 0; $i < $numberOfStructureElements; ++$i) {
            $structureStart = $document->getLogicalStartPage()[$i] - 1;
            $structureEnd = $document->getLogicalEndPage()[$i] - 1;

            $canvases = [];
            for ($j = $structureStart; $j < $structureEnd; ++$j) {
                $canvas = $this->router->generate(
                    'subugoe_iiif_canvas', [
                        'id' => $document->getId(),
                        'canvas' => $document->getPages()[$j],
                    ],
                    Router::ABSOLUTE_URL);
                $canvases[] = $canvas;
            }

            $structure = new Structure();
            $structure
                ->setId($this->router->generate('subugoe_iiif_range', [
                        'id' => $document->getId(),
                        'range' => $document->getLogicalIds()[$i],
                    ], Router::ABSOLUTE_URL)
                )
                ->setLabel($document->getLogicalLabels()[$i])
                ->setType($document->getLogicalTypes()[$i])
                ->setCanvases($canvases)
            ;

            $structures[] = $structure;
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
                'canvas' => $document->getPages()[0],
            ],
                Router::ABSOLUTE_URL)
            );

        return $sequences;
    }

    /**
     * @param string $documentId
     * @param string $canvasId
     *
     * @return Canvas
     */
    public function getCanvas(string $documentId, string $canvasId): Canvas
    {
        $images = $this->getImages($documentId, $canvasId);

        $canvas = new Canvas();
        $canvas
            ->setId($this->router->generate('subugoe_iiif_canvas', ['id' => $documentId, 'canvas' => $canvasId]))
            ->setLabel($canvasId)
            ->setImages($images);

        return $canvas;
    }

    /**
     * @param string $id
     * @param string $canvasId
     *
     * @return array
     */
    private function getImages(string $id, string $canvasId): array
    {
        return [$this->getImage($id, $canvasId)];
    }

    /**
     * @param string $id
     * @param string $imageId
     *
     * @return ImageResource
     */
    public function getImage(string $id, string $imageId): ImageResource
    {
        $document = $this->translator->getDocumentById($id);

        $imageParameters = [
            'identifier' => vsprintf('%s:%s', [$id, $imageId]),
            'region' => 'full',
            'size' => 'full',
            'rotation' => 0,
            'quality' => 'default',
            'format' => $document->getImageFormat(),
        ];

        $image = new ImageResource();
        $resource = new ResourceData();
        $mimes = new \Mimey\MimeTypes();

        $format = $mimes->getMimeType($document->getImageFormat());

        $resource
            ->setId($this->router->generate('subugoe_iiif_image', $imageParameters, Router::ABSOLUTE_URL))
            ->setFormat($format)
            ->setService(new Service());

        $image
            ->setId($this->router->generate('subugoe_iiif_imagepresentation', ['id' => $id, 'name' => $imageId], Router::ABSOLUTE_URL))
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
        $numberOfPages = count($document->getPages());

        for ($i = 0; $i < $numberOfPages; ++$i) {
            $canvas = new Canvas();
            $canvas
                ->setLabel($document->getPhysicalOrderPages()[$i])
                ->setId($this->router->generate('subugoe_iiif_canvas', [
                    'id' => $document->getId(),
                    'canvas' => $document->getPages()[$i], ],
                    Router::ABSOLUTE_URL)
                );
            $canvases[] = $canvas;
        }

        return $canvases;
    }

    /**
     * @param string $documentId
     * @param string $name
     *
     * @return Sequence
     */
    public function getSequence(string $documentId, $name): Sequence
    {
        $document = $this->translator->getDocumentById($documentId);
        $canvases = $this->getCanvases($document);

        $sequence = new Sequence();
        $sequence
            ->setId($this->router->generate('subugoe_iiif_sequence', [
                'id' => $documentId,
                'name' => $name,
            ],
                Router::ABSOLUTE_URL))
            ->setCanvases($canvases)
            ->setStartCanvas($this->router->generate('subugoe_iiif_canvas', [
                'id' => $documentId,
                'canvas' => $document->getPages()[0],
            ],
                Router::ABSOLUTE_URL));

        return $sequence;
    }
}
