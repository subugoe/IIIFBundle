<?php

declare(strict_types=1);

namespace Subugoe\IIIFBundle\Translator;

use GuzzleHttp\Client;
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
            ->setId($this->router->generate('_collection', ['id' => $collectionId], RouterInterface::ABSOLUTE_URL))
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
            $thumbnail->setId($this->router->generate('subugoe_iiif_image_base', ['identifier' => $collectionData['image']], RouterInterface::ABSOLUTE_URL));

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
            ->setType($this->getMappedDocumentType($solrDocument['docstrct']))
            ->setRightsOwner($solrDocument['rights_owner'] ?: [])
            ->setTitle($solrDocument['title'])
            ->setAuthors($solrDocument['creator'] ?: [])
            ->setPublishingPlaces($solrDocument['place_publish'] ?: [])
            ->setClassification($solrDocument['dc'])
            ->setPublishingYear((int) $solrDocument['year_publish'] ?: 0)
            ->setPublisher($solrDocument['publisher'] ?: [])
            ->setLanguage($solrDocument['lang'] ?: [])
            ->setImageFormat('jpg')
            ->setRenderings($this->getRenderings($id))
            ->setSeeAlso($this->getSeeAlso($solrDocument))
            ->setAdditionalIdentifiers($this->getAdditionalIdentifiers($solrDocument))
            ->setDescription('');

        for ($i = 0; $i < $numberOfLogicalStructures; ++$i) {
            $structure = new LogicalStructure();

            $label = (!empty(trim($solrDocument['log_label'][$i]))) ? $solrDocument['log_label'][$i] : $this->translator->trans($solrDocument['log_type'][$i]);

            $structure
                ->setId($solrDocument['log_id'][$i])
                ->setLabel($label)
                ->setType($solrDocument['log_type'][$i])
                ->setLevel((int) $solrDocument['log_level'][$i])
                ->setStartPage($solrDocument['log_start_page_index'][$i])
                ->setEndPage($solrDocument['log_end_page_index'][$i]);

            $document->addLogicalStructure($structure);
        }

        for ($i = 0; $i < $numberOfPhysicalStructures; ++$i) {
            $label = isset($solrDocument['phys_orderlabel'][$i]) ? $solrDocument['phys_orderlabel'][$i] : '';
            $physicalOrder = isset($solrDocument['phys_order'][$i]) ? $solrDocument['phys_order'][$i] : 0;

            $structure = new PhysicalStructure();
            $structure
                ->setIdentifier($solrDocument['page_key'][$i])
                ->setLabel($label)
                ->setOrder($physicalOrder)
                ->setPage($solrDocument['page'][$i])
                ->setAnnotation(isset($solrDocument['fulltext_ref'][$i]) ?
                    $this->router->generate('_fulltext',
                        [
                            'work' => $solrDocument['id'],
                            'page' => $solrDocument['page'][$i],
                        ], RouterInterface::ABSOLUTE_URL) :
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
            $parentSolr = $this->searchService->getDocumentById($solrDocument['parentdoc_work'][$i]);
            $parent = new Document();
            $parent
                ->setId($solrDocument['parentdoc_work'][$i])
                ->setType($this->getMappedDocumentType($solrDocument['parentdoc_type'][$i]))
                ->setTitle($parentSolr->getFields()['title']);

            $document->setParent($parent);
        }

        $document->setMetadata($this->getMetadata($solrDocument, $document));

        return $document;
    }

    /**
     * @param string $imageId
     *
     * @return Document
     */
    public function getDocumentByImageId(string $imageId): Document
    {
        $solrDocument = $this->searchService->getDocumentBy('page_key', $imageId, ['id']);

        return $this->getDocumentById($solrDocument['id']);
    }

    private function getAdditionalIdentifiers(DocumentInterface $solrDocument): array
    {
        $ids = [];
        foreach ($solrDocument['identifier'] as $identifier) {
            $parts = explode(' ', $identifier);

            $id = [];
            $id[$parts[0]] = $parts[1];

            $ids[] = $id;
        }

        return $ids;
    }

    private function translateLabel(string $label, int $counter = 1): string
    {
        return $this->translator->transChoice($label, $counter);
    }

    private function getMetadata(DocumentInterface $solrDocument, Document $document): array
    {
        $metadata = [];

        if (isset($solrDocument['summary_ref'])) {
            $client = new Client();
            $summary = $client->get($solrDocument['summary_ref'][0])->getBody()->getContents();

            $metadata[$this->translateLabel('summary')] = $summary;
        }

        if (isset($solrDocument['creator'])) {
            $author = $this->getLinkedMetadata($solrDocument['creator'], true, 'facet_creator_personal');

            if ($author !== []) {
                $metadata[$this->translateLabel('author', count($author))] = $author;
            }
        }

        if (isset($solrDocument['place_publish'])) {
            $place = $this->getLinkedMetadata($solrDocument['place_publish'], false);

            if ($place !== []) {
                $metadata[$this->translateLabel('place', count($place))] = $place;
            }
        }

        if (count($document->getParents()) > 0) {
            $metadata[$this->translateLabel('parent_work')] = sprintf('<a href="%s">%s</a>',
                $this->router->generate('_detail', ['id' => $document->getParents()[0]->getId()], RouterInterface::ABSOLUTE_URL),
                $document->getParents()[0]->getTitle()[0]);
        }

        $metadata[$this->translateLabel('year')] = (string) $solrDocument['year_publish'] ?: '0';

        if (isset($solrDocument['publisher'])) {
            $publisher = $this->getLinkedMetadata($solrDocument['publisher'], true, 'facet_publisher');

            if ($publisher !== []) {
                $metadata[$this->translateLabel('publisher')] = $publisher;
            }
        }

        if (is_array($solrDocument['lang'])) {
            $languages = [];
            foreach ($solrDocument['lang'] as $language) {
                if (!empty($language)) {
                    $language = $this->translator->trans($language);
                    $languages[] = $language;
                }
            }
            $metadata[$this->translateLabel('language', count($languages))] = $languages;
        }

        return $metadata;
    }

    private function getCollectionContent(string $id): string
    {
        $file = sprintf('%s/Resources/content/dc/%s.md', $this->rootDirectory, $id);
        $content = '';
        if (file_exists($file)) {
            $content = file_get_contents($file);
        }

        return $content;
    }

    private function getMappedDocumentType(string $doctype): string
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

    private function getRenderings(string $id): array
    {
        $renderings = [];

        $pdfRendering = new Rendering();
        $pdfRendering
            ->setFormat('application/pdf')
            ->setId($this->router->generate('_download_pdf', ['id' => $id],
                RouterInterface::ABSOLUTE_URL))
            ->setLabel('PDF download');

        $renderings[] = $pdfRendering;

        $mets = $this->router->generate('_mets', ['id' => $id], RouterInterface::ABSOLUTE_URL);

        $dfgViewer = new Rendering();
        $dfgViewer
            ->setId(sprintf('https://dfg-viewer.de/show/?set[mets]=%s', $mets))
            ->setFormat('text/html')
            ->setLabel('DFG-Viewer');

        $renderings[] = $dfgViewer;

        return $renderings;
    }

    private function getSeeAlso(DocumentInterface $document): array
    {
        $seeAlsos = [];
        $formats = [
              'bib' => [
                  'mimeType' => 'application/x-bibtex',
                  'profile' => 'http://www.bibtex.org/Format/',
              ],
              'ris' => [
                  'mimeType' => 'application/x-research-info-systems',
                  'profile' => 'http://referencemanager.com/sites/rm/files/m/direct_export_ris.pdf', ],
              'enw' => [
                  'mimeType' => 'application/x-endnote-refer',
                  'profile' => 'http://endnote.com/',
              ],
          ];

        foreach ($formats as $extension => $data) {
            $seeAlso = new SeeAlso();
            $seeAlso
                ->setId($this->router->generate('_download_export', ['id' => $document['id'], '_format' => $extension], RouterInterface::ABSOLUTE_URL))
                ->setFormat($data['mimeType'])
                ->setProfile($data['profile']);
            $seeAlsos[] = $seeAlso;
        }

        $mets = new SeeAlso();
        $mets
            ->setId($this->router->generate('_mets', ['id' => $document['id']], RouterInterface::ABSOLUTE_URL))
            ->setFormat('text/xml')
            ->setProfile('http://www.loc.gov/standards/mets/profile_docs/mets.profile.v2-0.xsd');

        $seeAlsos[] = $mets;

        return $seeAlsos;
    }

    private function getLinkedMetadata(array $metadata, bool $link, string $facet = ''): array
    {
        $metadataArr = [];

        if (is_array($metadata) && $metadata !== []) {
            foreach ($metadata as $key => $value) {
                if (!empty($value) && !empty($facet) && $link) {
                    $url = $this->router->generate('_homepage', ["filter[${key}][${facet}]" => $value], RouterInterface::ABSOLUTE_URL);
                    $href = sprintf('<a href="%s">%s</a>', $url, $value);
                    $metadataArr[] = $href;
                } elseif (!empty($value)) {
                    $metadataArr[] = $value;
                }
            }
        }

        return $metadataArr;
    }
}
