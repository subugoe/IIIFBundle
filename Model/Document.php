<?php

declare(strict_types=1);

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
    private $rightsOwner;

    /**
     * @var array
     */
    private $metadata;

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
     * @var int
     */
    private $publishingYear;

    /**
     * @var \DateTimeImmutable
     */
    private $indexingDate;

    /**
     * @var string
     */
    private $titlePage;

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
    private $physicalStructures = [];

    /**
     * @var string
     */
    private $imageFormat = 'jpg';

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $description;

    /**
     * @var array
     */
    private $parents = [];

    /**
     * @var array
     */
    private $renderings = [];

    /**
     * @var array
     */
    private $seeAlso = [];

    /**
     * @var array
     */
    private $additionalIdentifiers = [];

    /**
     * @var string
     */
    private $issue;

    /**
     * @var array
     */
    private $related = [];

    /**
     * @var array
     */
    private $logPartWork = [];

    /**
     * @var string
     */
    private $permaLink;

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
    public function setId(string $id): self
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
    public function setTitle(array $title): self
    {
        $this->title = $title;

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
    public function setRightsOwner(array $rightsOwner): self
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
    public function setAuthors(array $authors): self
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
    public function setPublishingPlaces(array $publishingPlaces): self
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
    public function setClassification(array $classification): self
    {
        $this->classification = $classification;

        return $this;
    }

    /**
     * @return int
     */
    public function getPublishingYear(): int
    {
        return $this->publishingYear;
    }

    /**
     * @param int $publishingYear
     *
     * @return Document
     */
    public function setPublishingYear(int $publishingYear): self
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
    public function setPublisher(array $publisher): self
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
    public function setLanguage(array $language): self
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
    public function setSubtitle(array $subtitle): self
    {
        $this->subtitle = $subtitle;

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
    public function setImageFormat(string $imageFormat): self
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
    public function setLogicalStructures(array $logicalStructures): self
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

    /**
     * @param int $id
     *
     * @return LogicalStructure
     */
    public function getLogicalStructure(int $id): LogicalStructure
    {
        return $this->logicalStructures[$id];
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return Document
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return array
     */
    public function getPhysicalStructures(): array
    {
        return $this->physicalStructures;
    }

    /**
     * @param int $id
     *
     * @return PhysicalStructure
     */
    public function getPhysicalStructure(int $id): PhysicalStructure
    {
        return $this->physicalStructures[$id];
    }

    /**
     * @param array $physicalStructures
     *
     * @return Document
     */
    public function setPhysicalStructures(array $physicalStructures): self
    {
        $this->physicalStructures = $physicalStructures;

        return $this;
    }

    /**
     * @param PhysicalStructure $structure
     */
    public function addPhysicalStructure(PhysicalStructure $structure)
    {
        $this->physicalStructures[] = $structure;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return Document
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return array
     */
    public function getParents(): array
    {
        return $this->parents;
    }

    /**
     * @param array $parents
     *
     * @return Document
     */
    public function setParents(array $parents): self
    {
        $this->parents = $parents;

        return $this;
    }

    /**
     * @param Document $document
     */
    public function setParent(self $document)
    {
        $this->parents[] = $document;
    }

    /**
     * @return array
     */
    public function getRenderings(): array
    {
        return $this->renderings;
    }

    /**
     * @param array $renderings
     *
     * @return Document
     */
    public function setRenderings(array $renderings): self
    {
        $this->renderings = $renderings;

        return $this;
    }

    /**
     * @return array
     */
    public function getSeeAlso(): array
    {
        return $this->seeAlso;
    }

    /**
     * @param array $seeAlso
     *
     * @return Document
     */
    public function setSeeAlso(array $seeAlso): self
    {
        $this->seeAlso = $seeAlso;

        return $this;
    }

    /**
     * @return array
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }

    /**
     * @param array $metadata
     *
     * @return Document
     */
    public function setMetadata(array $metadata): self
    {
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * @return array
     */
    public function getAdditionalIdentifiers(): array
    {
        return $this->additionalIdentifiers;
    }

    /**
     * @param array $additionalIdentifiers
     *
     * @return Document
     */
    public function setAdditionalIdentifiers(array $additionalIdentifiers): self
    {
        $this->additionalIdentifiers = $additionalIdentifiers;

        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function addMetadata(string $key, string $value)
    {
        $this->metadata[$key] = $value;
    }

    /**
     * @return array
     */
    public function getRelated(): array
    {
        return $this->related;
    }

    /**
     * @param array $related
     *
     * @return Document
     */
    public function setRelated(array $related): self
    {
        $this->related = $related;

        return $this;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getIndexingDate(): \DateTimeImmutable
    {
        return $this->indexingDate;
    }

    /**
     * @param \DateTimeImmutable $indexingDate
     *
     * @return Document
     */
    public function setIndexingDate(\DateTimeImmutable $indexingDate): self
    {
        $this->indexingDate = $indexingDate;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitlePage(): string
    {
        return $this->titlePage;
    }

    /**
     * @param string $titlePage
     *
     * @return Document
     */
    public function setTitlePage(string $titlePage): self
    {
        $this->titlePage = $titlePage;

        return $this;
    }

    /**
     * @return string
     */
    public function getIssue(): string
    {
        return $this->issue;
    }

    /**
     * @param string $issue
     *
     * @return Document
     */
    public function setIssue(string $issue): self
    {
        $this->issue = $issue;

        return $this;
    }

    /**
     * @return array
     */
    public function getLogPartWork(): array
    {
        return $this->logPartWork;
    }

    /**
     * @param array $logPartWork
     *
     * @return Document
     */
    public function setLogPartWork(array $logPartWork): self
    {
        $this->logPartWork = $logPartWork;

        return $this;
    }

    /**
     * @return string
     */
    public function getPermaLink(): string
    {
        return $this->permaLink;
    }

    /**
     * @param string $permaLink
     *
     * @return Document
     */
    public function setPermaLink(string $permaLink): self
    {
        $this->permaLink = $permaLink;

        return $this;
    }
}
