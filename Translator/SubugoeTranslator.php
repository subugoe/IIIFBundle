<?php

declare(strict_types=1);

namespace Subugoe\IIIFBundle\Translator;

use Subugoe\FindBundle\Service\SearchService;
use Subugoe\IIIFModel\Model\Document;
use Subugoe\IIIFModel\Model\DocumentInterface;
use Subugoe\IIIFModel\Model\DocumentTypes;
use Subugoe\IIIFModel\Model\LogicalStructure;
use Subugoe\IIIFModel\Model\PhysicalStructure;
use Subugoe\IIIFModel\Model\Presentation\Collection;
use Subugoe\IIIFModel\Model\Presentation\Collections;
use Subugoe\IIIFModel\Model\Presentation\Image;
use Subugoe\IIIFModel\Model\Presentation\Related;
use Subugoe\IIIFModel\Model\Presentation\Rendering;
use Subugoe\IIIFModel\Model\Presentation\SeeAlso;
use Symfony\Component\Routing\RouterInterface;

class SubugoeTranslator implements TranslatorInterface
{
    private readonly SearchService $searchService;

    /**
     * @var \Symfony\Component\Translation\TranslatorInterface
     */
    private $translator;

    public function __construct(SearchService $searchService, private readonly RouterInterface $router, \Symfony\Component\Translation\TranslatorInterface $translator, private readonly string $rootDirectory, private readonly array $collections)
    {
        $this->searchService = $searchService;
        $this->translator = $translator;
    }

    public function getCollectionById(string $collectionId): Collection
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

    public function getDocumentBy(string $field, string $value, array $fields = []): DocumentInterface
    {
        // TODO: Implement getDocumentBy() method.
    }

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
            ->setDescription('')
            ->setRelated($this->getRelated($solrDocument['catalogue'] ?: []));

        for ($i = 0; $i < $numberOfLogicalStructures; ++$i) {
            $structure = new LogicalStructure();

            $label = (in_array(trim((string) $solrDocument['log_label'][$i]), ['', '0'], true)) ? $this->translator->trans($solrDocument['log_type'][$i]) : $solrDocument['log_label'][$i];

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
            $label = $solrDocument['phys_orderlabel'][$i] ?? '';
            $physicalOrder = $solrDocument['phys_order'][$i] ?? 0;

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
            $parent = new Document();
            $parent
                ->setId($solrDocument['parentdoc_work'][$i])
                ->setType($this->getMappedDocumentType($solrDocument['parentdoc_type'][$i]))
                ->setTitle([$solrDocument['parentdoc_label'][$i]]);

            $document->setParent($parent);
        }

        $document->setMetadata($this->getMetadata($solrDocument, $document));

        return $document;
    }

    public function getDocumentByImageId(string $imageId): Document
    {
        $solrDocument = $this->searchService->getDocumentBy('page_key', $imageId, ['id']);

        return $this->getDocumentById($solrDocument['id']);
    }

    private function getAdditionalIdentifiers(DocumentInterface $solrDocument): array
    {
        $ids = [];
        foreach ($solrDocument['identifier'] as $identifier) {
            $parts = explode(' ', (string) $identifier);

            $id = [];
            $id[$parts[0]] = $parts[1];

            $ids[] = $id;
        }

        return $ids;
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

    private function getLinkedMetadata(array $metadata, bool $link, string $facet = ''): array
    {
        $metadataArr = [];

        if (is_array($metadata) && [] !== $metadata) {
            foreach ($metadata as $key => $value) {
                if (!empty($value) && ('' !== $facet && '0' !== $facet) && $link) {
                    $url = $this->router->generate('_homepage', ["filter[{$key}][{$facet}]" => $value], RouterInterface::ABSOLUTE_URL);
                    $href = sprintf('<a href="%s">%s</a>', $url, $value);
                    $metadataArr[] = $href;
                } elseif (!empty($value)) {
                    $metadataArr[] = $value;
                }
            }
        }

        return $metadataArr;
    }

    private function getMappedDocumentType(string $doctype): string
    {
        $typeMapping = [
            'bundle' => DocumentTypes::BUNDLE,
            'contained_work' => DocumentTypes::CONTAINED_WORK,
            'file' => DocumentTypes::FILE,
            'folder' => DocumentTypes::FOLDER,
            'manuscript' => DocumentTypes::MONOGRAPH,
            'map' => DocumentTypes::MAP,
            'monograph' => DocumentTypes::MONOGRAPH,
            'multivolume_work' => DocumentTypes::MULTIVOLUME_WORK,
            'periodical' => DocumentTypes::VOLUME,
            'periodicalvolume' => DocumentTypes::VOLUME,
            'volume' => DocumentTypes::VOLUME,
        ];

        if (array_key_exists($doctype, $typeMapping)) {
            return $typeMapping[$doctype];
        }

        return DocumentTypes::UNKNOWN;
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

            if ([] !== $author) {
                $metadata[$this->translateLabel('author', count($author))] = $author;
            }
        }

        if (isset($solrDocument['place_publish'])) {
            $place = $this->getLinkedMetadata($solrDocument['place_publish'], false);

            if ([] !== $place) {
                $metadata[$this->translateLabel('place', count($place))] = $place;
            }
        }

        if ([] !== $document->getParents()) {
            $metadata[$this->translateLabel('parent_work')] = sprintf('<a href="%s">%s</a>',
                $this->router->generate('_volumes', ['id' => $document->getParents()[0]->getId()], RouterInterface::ABSOLUTE_URL),
                $document->getParents()[0]->getTitle()[0]);
        }

        $metadata[$this->translateLabel('year')] = (string) $solrDocument['year_publish'] ?: '0';

        if (isset($solrDocument['publisher'])) {
            $publisher = $this->getLinkedMetadata($solrDocument['publisher'], true, 'facet_publisher');

            if ([] !== $publisher) {
                $metadata[$this->translateLabel('publisher')] = $publisher;
            }
        }

        if (is_array($solrDocument['lang'])) {
            $languages = [];
            foreach ($solrDocument['lang'] as $language) {
                if (!empty($language)) {
                    $language = $this->translator->trans(sprintf('languages.%s', $language));
                    $languages[] = $language;
                }
            }
            $metadata[$this->translateLabel('language', count($languages))] = $languages;
        }

        return $metadata;
    }

    private function getRelated(array $catalogue): array
    {
        $relatedArr = [];
        if (is_array($catalogue) && [] !== $catalogue) {
            foreach ($catalogue as $value) {
                $catalogueArr = explode(' ', trim((string) $value));
                $related = new Related();
                $id = $catalogueArr[1];
                $label = $catalogueArr[0];
                if ('' !== $id && '0' !== $id && ('' !== $label && '0' !== $label)) {
                    $related->setId($id);
                    $related->setLabel($label);
                    $related->setFormat('text/html');
                    $relatedArr[] = $related;
                }
            }
        }

        return $relatedArr;
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

    private function translateLabel(string $label, int $counter = 1): string
    {
        return $this->translator->transChoice($label, $counter);
    }
}
