<?php

declare(strict_types=1);

namespace Subugoe\IIIFBundle\Translator;

use Subugoe\FindBundle\Service\SearchService;
use Subugoe\IIIFBundle\Model\Document;
use Subugoe\IIIFBundle\Model\LogicalStructure;

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
            ->setPhysicalOrderPages($solrDocument['phys_orderlabel']);

        for ($i = 0; $i < $numberOfLogicalStructures; ++$i) {
            $structure = new LogicalStructure();

            $structure
                ->setId($solrDocument['log_id'][$i])
                ->setLabel($solrDocument['log_label'][$i])
                ->setType($solrDocument['log_type'][$i])
                ->setLevel($solrDocument['log_level'][$i])
                ->setStartPage($solrDocument['log_start_page_index'][$i])
                ->setEndPage($solrDocument['log_end_page_index'][$i]);

            $document->addLogicalStructure($structure);
        }

        return $document;
    }
}
