<?php

declare(strict_types=1);

namespace Subugoe\IIIFBundle\Translator;

use Subugoe\FindBundle\Service\SearchService;
use Subugoe\IIIFBundle\Model\Document;
use Subugoe\IIIFBundle\Model\DocumentTypes;
use Subugoe\IIIFBundle\Model\LogicalStructure;
use Subugoe\IIIFBundle\Model\PhysicalStructure;

class SubugoeTranslator implements TranslatorInterface
{
    /**
     * @var SearchService
     */
    private $searchService;

    /**
     * SubugoeTranslator constructor.
     *
     * @param SearchService $searchService
     */
    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
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
        $numberOfPhysicalStructures = count($solrDocument['phys_order']);

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
                ->setFilename(vsprintf(
                    '%s/%s.%s', [
                        $solrDocument['id'],
                        $solrDocument['page'][$i],
                        'tif',
                    ]
                ));

            $document->addPhysicalStructure($structure);
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
}
