<?php

namespace Subugoe\IIIFBundle\Model;

/**
 * Document for holding generic data.
 */
class Document
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var array
     */
    private $title = [];

    /**
     * @var array
     */
    private $subtitle = [];

    /**
     * @var array
     */
    private $pages;

    /**
     * @var array
     */
    private $rightsOwner;

    /**
     * @var array
     */
    private $authors;

    /**
     * @var array
     */
    private $classification;

    /**
     * @var array
     */
    private $publishingPlaces;

    /**
     * @var string
     */
    private $publishingYear;

    /**
     * @var array
     */
    private $publisher;

    /**
     * @var array
     */
    private $language;

    /**
     * @var array
     */
    private $logicalIds;

    /**
     * @var array
     */
    private $logicalLabels;

    /**
     * @var array
     */
    private $logicalTypes;

    private $logicalStartPage;

    private $logicalEndPage;

    /**
     * @var array
     */
    private $physicalOrderPages;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return Document
     */
    public function setId(string $id): Document
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return array
     */
    public function getTitle(): array
    {
        return $this->title;
    }

    /**
     * @param array $title
     *
     * @return Document
     */
    public function setTitle(array $title): Document
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return array
     */
    public function getPages(): array
    {
        return $this->pages;
    }

    /**
     * @param array $pages
     *
     * @return Document
     */
    public function setPages(array $pages): Document
    {
        $this->pages = $pages;

        return $this;
    }

    /**
     * @return array
     */
    public function getRightsOwner(): array
    {
        return $this->rightsOwner;
    }

    /**
     * @param array $rightsOwner
     *
     * @return Document
     */
    public function setRightsOwner(array $rightsOwner): Document
    {
        $this->rightsOwner = $rightsOwner;

        return $this;
    }

    /**
     * @return array
     */
    public function getAuthors(): array
    {
        return $this->authors;
    }

    /**
     * @param array $authors
     *
     * @return Document
     */
    public function setAuthors(array $authors): Document
    {
        $this->authors = $authors;

        return $this;
    }

    /**
     * @return array
     */
    public function getPublishingPlaces(): array
    {
        return $this->publishingPlaces;
    }

    /**
     * @param array $publishingPlaces
     *
     * @return Document
     */
    public function setPublishingPlaces(array $publishingPlaces): Document
    {
        $this->publishingPlaces = $publishingPlaces;

        return $this;
    }

    /**
     * @return array
     */
    public function getClassification(): array
    {
        return $this->classification;
    }

    /**
     * @param array $classification
     *
     * @return Document
     */
    public function setClassification(array $classification): Document
    {
        $this->classification = $classification;

        return $this;
    }

    /**
     * @return string
     */
    public function getPublishingYear(): string
    {
        return $this->publishingYear;
    }

    /**
     * @param string $publishingYear
     *
     * @return Document
     */
    public function setPublishingYear(string $publishingYear): Document
    {
        $this->publishingYear = $publishingYear;

        return $this;
    }

    /**
     * @return array
     */
    public function getPublisher(): array
    {
        return $this->publisher;
    }

    /**
     * @param array $publisher
     *
     * @return Document
     */
    public function setPublisher(array $publisher): Document
    {
        $this->publisher = $publisher;

        return $this;
    }

    /**
     * @return array
     */
    public function getLanguage(): array
    {
        return $this->language;
    }

    /**
     * @param array $language
     *
     * @return Document
     */
    public function setLanguage(array $language): Document
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return array
     */
    public function getSubtitle(): array
    {
        return $this->subtitle;
    }

    /**
     * @param array $subtitle
     *
     * @return Document
     */
    public function setSubtitle(array $subtitle): Document
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * @return array
     */
    public function getPhysicalOrderPages(): array
    {
        return $this->physicalOrderPages;
    }

    /**
     * @param array $physicalOrderPages
     *
     * @return Document
     */
    public function setPhysicalOrderPages(array $physicalOrderPages): Document
    {
        $this->physicalOrderPages = $physicalOrderPages;

        return $this;
    }

    /**
     * @return array
     */
    public function getLogicalIds(): array
    {
        return $this->logicalIds;
    }

    /**
     * @param array $logicalIds
     *
     * @return Document
     */
    public function setLogicalIds(array $logicalIds): Document
    {
        $this->logicalIds = $logicalIds;

        return $this;
    }

    /**
     * @return array
     */
    public function getLogicalLabels(): array
    {
        return $this->logicalLabels;
    }

    /**
     * @param array $logicalLabels
     *
     * @return Document
     */
    public function setLogicalLabels(array $logicalLabels): Document
    {
        $this->logicalLabels = $logicalLabels;

        return $this;
    }

    /**
     * @return array
     */
    public function getLogicalTypes(): array
    {
        return $this->logicalTypes;
    }

    /**
     * @param array $logType
     *
     * @return Document
     */
    public function setLogicalTypes(array $logType): Document
    {
        $this->logicalTypes = $logType;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLogicalStartPage()
    {
        return $this->logicalStartPage;
    }

    /**
     * @param mixed $logicalStartPage
     *
     * @return Document
     */
    public function setLogicalStartPage($logicalStartPage)
    {
        $this->logicalStartPage = $logicalStartPage;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLogicalEndPage()
    {
        return $this->logicalEndPage;
    }

    /**
     * @param mixed $logicalEndPage
     *
     * @return Document
     */
    public function setLogicalEndPage($logicalEndPage)
    {
        $this->logicalEndPage = $logicalEndPage;

        return $this;
    }
}
