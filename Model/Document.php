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
    private $logicalStructures = [];

    /**
     * @var array
     */
    private $physicalOrderPages;

    /**
     * @var string
     */
    private $imageFormat = 'jpg';

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
     * @return string
     */
    public function getImageFormat(): string
    {
        return $this->imageFormat;
    }

    /**
     * @param string $imageFormat
     *
     * @return Document
     */
    public function setImageFormat(string $imageFormat): Document
    {
        $this->imageFormat = $imageFormat;

        return $this;
    }

    /**
     * @return array
     */
    public function getLogicalStructures(): array
    {
        return $this->logicalStructures;
    }

    /**
     * @param array $logicalStructures
     *
     * @return Document
     */
    public function setLogicalStructures(array $logicalStructures): Document
    {
        $this->logicalStructures = $logicalStructures;

        return $this;
    }

    /**
     * @param LogicalStructure $structure
     */
    public function addLogicalStructure(LogicalStructure $structure)
    {
        $this->logicalStructures[] = $structure;
    }
}
