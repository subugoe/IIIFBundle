<?php

declare(strict_types=1);

namespace Subugoe\IIIFBundle\Translator;

use Solarium\QueryType\Select\Result\DocumentInterface;
use Subugoe\FindBundle\Service\SearchService;
use Subugoe\IIIFBundle\Model\Document;
use Subugoe\IIIFBundle\Model\DocumentTypes;
use Subugoe\IIIFBundle\Model\LogicalStructure;
use Subugoe\IIIFBundle\Model\PhysicalStructure;
use Subugoe\IIIFBundle\Model\Presentation\Collection;
use Subugoe\IIIFBundle\Model\Presentation\Collections;
use Subugoe\IIIFBundle\Model\Presentation\Image;
use Subugoe\IIIFBundle\Model\Presentation\Rendering;
use Subugoe\IIIFBundle\Model\Presentation\SeeAlso;
use Symfony\Component\Routing\RouterInterface;

class SubugoeTranslator implements TranslatorInterface
{
    /**
     * @var SearchService
     */
    private $searchService;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var \Symfony\Component\Translation\TranslatorInterface
     */
    private $translator;

    /**
     * @var string
     */
    private $rootDirectory;

    /**
     * @var array
     */
    private $collections;

    /**
     * SubugoeTranslator constructor.
     *
     * @param SearchService                                      $searchService
     * @param RouterInterface                                    $router
     * @param \Symfony\Component\Translation\TranslatorInterface $translator
     * @param string                                             $rootDirectory
     */
    public function __construct(SearchService $searchService, RouterInterface $router, \Symfony\Component\Translation\TranslatorInterface $translator, string $rootDirectory, array $collections)
    {
        $this->searchService = $searchService;
        $this->router = $router;
        $this->translator = $translator;
        $this->rootDirectory = $rootDirectory;
        $this->collections = $collections;
    }

    /**
     * @param string $collectionId
     *
     * @return Collection
     */
    public function getCollectionById(string $collectionId)
    {
        $collection = new Collection();
        $collection
            ->setId($this->router->generate('_collection', ['id' => $collectionId], RouterInterface::NETWORK_PATH))
            ->setLabel($this->translator->trans($collectionId))
            ->setDescription($this->getCollectionContent($collectionId))
        ;

        return $collection;
    }

    /**
     * @return Collections
     */
    public function getCollections()
    {
        $collections = new Collections();
        foreach ($this->collections as $collectionData) {
            $thumbnail = new Image();
            $thumbnail->setId($this->router->generate('subugoe_iiif_image_base', ['identifier' => $collectionData['image']], RouterInterface::NETWORK_PATH));

            $collection = $this->getCollectionById($collectionData['id']);
            $collection->setThumbnail($thumbnail);
            $collections->addCollection($collection);
        }

        return $collections;
    }

    /**
     * @param string $id
     *
     * @return Document
     */
    public function getDocumentById(string $id): Document
    {
        $document = new Document();
        $solrDocument = $this->searchService->getDocumentById($id);
        $numberOfLogicalStructures = count($solrDocument['log_id']);
        $numberOfPhysicalStructures = count($solrDocument['page']);

        $document
            ->setId($id)
            ->setType($this->getMappedDocumentType($solrDocument['doctype']))
            ->setRightsOwner($solrDocument['rights_owner'] ?: [])
            ->setTitle($solrDocument['title'])
            ->setAuthors($solrDocument['creator'] ?: [])
            ->setPublishingPlaces($solrDocument['place_publish'] ?: [])
            ->setClassification($solrDocument['dc'])
            ->setPublishingYear((int) $solrDocument['year_publish'] ?: 0)
            ->setPublisher($solrDocument['publisher'] ?: [])
            ->setLanguage($solrDocument['lang'] ?: [])
            ->setImageFormat($solrDocument['image_format'])
            ->setRenderings([$this->getPdfRendering($id)])
            ->setSeeAlso($this->getSeeAlso($solrDocument))
            ->setDescription('');

        for ($i = 0; $i < $numberOfLogicalStructures; ++$i) {
            $structure = new LogicalStructure();

            $structure
                ->setId($solrDocument['log_id'][$i])
                ->setLabel($solrDocument['log_label'][$i])
                ->setType($solrDocument['log_type'][$i])
                ->setLevel((int) $solrDocument['log_level'][$i])
                ->setStartPage($solrDocument['log_start_page_index'][$i])
                ->setEndPage($solrDocument['log_end_page_index'][$i]);

            $document->addLogicalStructure($structure);
        }

        for ($i = 0; $i < $numberOfPhysicalStructures; ++$i) {
            $structure = new PhysicalStructure();
            $structure
                ->setIdentifier($solrDocument['page_key'][$i])
                ->setLabel($solrDocument['phys_orderlabel'][$i])
                ->setOrder($solrDocument['phys_order'][$i])
                ->setPage($solrDocument['page'][$i])
                ->setAnnotation(isset($solrDocument['fulltext_ref'][$i]) ?
                    $this->router->generate('_fulltext',
                        [
                            'work' => $solrDocument['id'],
                            'page' => $solrDocument['page'][$i],
                        ], RouterInterface::NETWORK_PATH) :
                    ''
                )
                ->setFilename(vsprintf(
                    '%s/%s.%s', [
                        $solrDocument['id'],
                        $solrDocument['page'][$i],
                        'tif',
                    ]
                ));

            $document->addPhysicalStructure($structure);
        }

        $numberOfParentDocuments = count($solrDocument['parentdoc_work']);
        for ($i = 0; $i < $numberOfParentDocuments; ++$i) {
            $parent = new Document();
            $parent
                ->setId($solrDocument['parentdoc_work'][$i])
                ->setType($this->getMappedDocumentType($solrDocument['parentdoc_type'][$i]))
                ->setTitle([$solrDocument['parentdoc_label'][$i]]);

            $document->setParent($parent);
        }

        return $document;
    }

    /**
     * @param string $imageId
     *
     * @return Document
     */
    public function getDocumentByImageId(string $imageId): Document
    {
        $solrDocument = $this->searchService->getDocumentBy('page_key', $imageId);

        return $this->getDocumentById($solrDocument['id']);
    }

    /**
     * @param string $id
     *
     * @return string
     */
    private function getCollectionContent($id)
    {
        $file = sprintf('%s/Resources/content/dc/%s.md', $this->rootDirectory, $id);
        $content = '';
        if (file_exists($file)) {
            $content = file_get_contents($file);
        }

        return $content;
    }

    /**
     * @param string $doctype
     *
     * @return string
     */
    private function getMappedDocumentType(string $doctype)
    {
        $typeMapping = [
            'monograph' => DocumentTypes::MONOGRAPH,
            'periodicalvolume' => DocumentTypes::ISSUE,
            'volume' => DocumentTypes::VOLUME,
            'periodical' => DocumentTypes::PERIODICAL,
            'multivolume_work' => DocumentTypes::MULTIVOLUME_WORK,
            'multivolumework' => DocumentTypes::MULTIVOLUME_WORK,
            'folder' => DocumentTypes::MULTIVOLUME_WORK,
            'manuscript' => DocumentTypes::MONOGRAPH,
        ];

        if (array_key_exists($doctype, $typeMapping)) {
            return $typeMapping[$doctype];
        }

        return DocumentTypes::UNKNOWN;
    }

    /**
     * @param string $id
     *
     * @return Rendering
     */
    private function getPdfRendering($id): Rendering
    {
        $pdfRendering = new Rendering();
        $pdfRendering
            ->setFormat('application/pdf')
            ->setId($this->router->generate('_download_pdf', ['id' => $id],
                RouterInterface::NETWORK_PATH))
            ->setLabel('PDF download');

        return $pdfRendering;
    }

    /**
     * @param DocumentInterface $document
     *
     * @return array
     */
    private function getSeeAlso(DocumentInterface $document)
    {
        $seeAlsos = [];
        $formats = [
              'bib' => 'application/x-bibtex',
              'ris' => 'application/x-research-info-systems',
              'enw' => 'application/x-endnote-refer',
          ];

        foreach ($formats as $extension => $mimeType) {
            $seeAlso = new SeeAlso();
            $seeAlso
                ->setId($this->router->generate('_download_export', ['id' => $document['id'], '_format' => $extension], RouterInterface::NETWORK_PATH))
                ->setFormat($mimeType);
            $seeAlsos[] = $seeAlso;
        }

        if (isset($document['summary_ref'])) {
            foreach ($document['summary_ref'] as $summaryReference) {
                $seeAlso = new SeeAlso();
                $seeAlso
                    ->setId($summaryReference)
                    ->setFormat('text/html');
                $seeAlsos[] = $seeAlso;
            }
        }

        return $seeAlsos;
    }
}
