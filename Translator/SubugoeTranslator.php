<?php

namespace Subugoe\IIIFBundle\Translator;

use Subugoe\FindBundle\Service\SearchService;
use Subugoe\IIIFBundle\Model\Document;

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
        $document
            ->setId($id)
            ->setPages($solrDocument['page'])
            ->setRightsOwner($solrDocument['attribution'] ?: [])
            ->setTitle($solrDocument['title'])
            ->setAuthors($solrDocument['creator'] ?: [])
            ->setPublishingPlaces($solrDocument['place_publish'] ?: [])
            ->setClassification($solrDocument['dc'])
            ->setPublishingYear($solrDocument['year_publish'] ?: '')
            ->setPublisher($solrDocument['publisher'] ?: [])
            ->setLanguage($solrDocument['lang'])
            ->setImageFormat($solrDocument['image_format'])
            ->setLogicalIds($solrDocument['log_id'])
            ->setLogicalLabels($solrDocument['log_label'])
            ->setLogicalTypes($solrDocument['log_type'])
            ->setLogicalStartPage($solrDocument['log_start_page_index'])
            ->setLogicalEndPage($solrDocument['log_end_page_index'])
            ->setPhysicalOrderPages($solrDocument['phys_orderlabel']);

        return $document;
    }
}
