<?php

declare(strict_types=1);

namespace Subugoe\IIIFBundle\Model;

/**
 * Document for holding generic data.
 */
class Document implements DocumentInterface
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
     * @var string
     */
    private $permaLink;

    /**
     * @var string
     */
    private $license = '';

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
     * @return DocumentInterface
     */
    public function setId(string $id): DocumentInterface
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
     * @return DocumentInterface
     */
    public function setTitle(array $title): DocumentInterface
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
     * @return DocumentInterface
     */
    public function setRightsOwner(array $rightsOwner): DocumentInterface
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
     * @return DocumentInterface
     */
    public function setAuthors(array $authors): DocumentInterface
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
     * @return DocumentInterface
     */
    public function setPublishingPlaces(array $publishingPlaces): DocumentInterface
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
     * @return DocumentInterface
     */
    public function setClassification(array $classification): DocumentInterface
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
     * @return DocumentInterface
     */
    public function setPublishingYear(int $publishingYear): DocumentInterface
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
     * @return DocumentInterface
     */
    public function setPublisher(array $publisher): DocumentInterface
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
     * @return DocumentInterface
     */
    public function setLanguage(array $language): DocumentInterface
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
     * @return DocumentInterface
     */
    public function setSubtitle(array $subtitle): DocumentInterface
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
     * @return DocumentInterface
     */
    public function setImageFormat(string $imageFormat): DocumentInterface
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
     * @return DocumentInterface
     */
    public function setLogicalStructures(array $logicalStructures): DocumentInterface
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
     * @return DocumentInterface
     */
    public function setType(string $type): DocumentInterface
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
     * @return DocumentInterface
     */
    public function setPhysicalStructures(array $physicalStructures): DocumentInterface
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
     * @return DocumentInterface
     */
    public function setDescription(string $description): DocumentInterface
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
     * @return DocumentInterface
     */
    public function setParents(array $parents): DocumentInterface
    {
        $this->parents = $parents;

        return $this;
    }

    /**
     * @param Document $document
     */
    public function setParent(DocumentInterface $document)
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
     * @return DocumentInterface
     */
    public function setRenderings(array $renderings): DocumentInterface
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
     * @return DocumentInterface
     */
    public function setSeeAlso(array $seeAlso): DocumentInterface
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
     * @return DocumentInterface
     */
    public function setMetadata(array $metadata): DocumentInterface
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
     * @return DocumentInterface
     */
    public function setAdditionalIdentifiers(array $additionalIdentifiers): DocumentInterface
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
     * @return DocumentInterface
     */
    public function setRelated(array $related): DocumentInterface
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
     * @return DocumentInterface
     */
    public function setIndexingDate(\DateTimeImmutable $indexingDate): DocumentInterface
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
     * @return DocumentInterface
     */
    public function setTitlePage(string $titlePage): DocumentInterface
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
     * @return DocumentInterface
     */
    public function setIssue(string $issue): DocumentInterface
    {
        $this->issue = $issue;

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
     * @return DocumentInterface
     */
    public function setPermaLink(string $permaLink): DocumentInterface
    {
        $this->permaLink = $permaLink;

        return $this;
    }

    /**
     * @return string
     */
    public function getLicense(): string
    {
        return $this->license;
    }

    /**
     * @param string $license
     *
     * @return DocumentInterface
     */
    public function setLicense(string $license): DocumentInterface
    {
        $this->license = $license;

        return $this;
    }
}
