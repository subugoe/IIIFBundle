<?php

declare(strict_types=1);

namespace Subugoe\IIIFBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;

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
     * @var ArrayCollection
     */
    private $title;

    /**
     * @var ArrayCollection
     */
    private $subtitle;

    /**
     * @var ArrayCollection
     */
    private $rightsOwner;

    /**
     * @var ArrayCollection
     */
    private $metadata;

    /**
     * @var ArrayCollection
     */
    private $authors;

    /**
     * @var ArrayCollection
     */
    private $classification;

    /**
     * @var ArrayCollection
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
     * @var ArrayCollection
     */
    private $publisher;

    /**
     * @var ArrayCollection
     */
    private $language;

    /**
     * @var ArrayCollection
     */
    private $logicalStructures;

    /**
     * @var ArrayCollection
     */
    private $physicalStructures;

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
     * @var ArrayCollection
     */
    private $parents;

    /**
     * @var ArrayCollection
     */
    private $renderings;

    /**
     * @var ArrayCollection
     */
    private $seeAlso;

    /**
     * @var ArrayCollection
     */
    private $additionalIdentifiers;

    /**
     * @var string
     */
    private $issue;

    /**
     * @var ArrayCollection
     */
    private $related;

    /**
     * @var string
     */
    private $permaLink;

    /**
     * @var string
     */
    private $license = '';

    public function __construct()
    {
        $this->title = new ArrayCollection();
        $this->subtitle = new ArrayCollection();
        $this->rightsOwner = new ArrayCollection();
        $this->metadata = new ArrayCollection();
        $this->authors = new ArrayCollection();
        $this->classification = new ArrayCollection();
        $this->publishingPlaces = new ArrayCollection();
        $this->publisher = new ArrayCollection();
        $this->language = new ArrayCollection();
        $this->logicalStructures = new ArrayCollection();
        $this->physicalStructures = new ArrayCollection();
        $this->parents = new ArrayCollection();
        $this->renderings = new ArrayCollection();
        $this->seeAlso = new ArrayCollection();
        $this->additionalIdentifiers = new ArrayCollection();
        $this->related = new ArrayCollection();
    }

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
     * @return ArrayCollection
     */
    public function getTitle(): ArrayCollection
    {
        return $this->title;
    }

    /**
     * @param ArrayCollection $title
     *
     * @return DocumentInterface
     */
    public function setTitle(ArrayCollection $title): DocumentInterface
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getRightsOwner(): ArrayCollection
    {
        return $this->rightsOwner;
    }

    /**
     * @param ArrayCollection $rightsOwner
     *
     * @return DocumentInterface
     */
    public function setRightsOwner(ArrayCollection $rightsOwner): DocumentInterface
    {
        $this->rightsOwner = $rightsOwner;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getAuthors(): ArrayCollection
    {
        return $this->authors;
    }

    /**
     * @param ArrayCollection $authors
     *
     * @return DocumentInterface
     */
    public function setAuthors(ArrayCollection $authors): DocumentInterface
    {
        $this->authors = $authors;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getPublishingPlaces(): ArrayCollection
    {
        return $this->publishingPlaces;
    }

    /**
     * @param ArrayCollection $publishingPlaces
     *
     * @return DocumentInterface
     */
    public function setPublishingPlaces(ArrayCollection $publishingPlaces): DocumentInterface
    {
        $this->publishingPlaces = $publishingPlaces;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getClassification(): ArrayCollection
    {
        return $this->classification;
    }

    /**
     * @param ArrayCollection $classification
     *
     * @return DocumentInterface
     */
    public function setClassification(ArrayCollection $classification): DocumentInterface
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
     * @return ArrayCollection
     */
    public function getPublisher(): ArrayCollection
    {
        return $this->publisher;
    }

    /**
     * @param ArrayCollection $publisher
     *
     * @return DocumentInterface
     */
    public function setPublisher(ArrayCollection $publisher): DocumentInterface
    {
        $this->publisher = $publisher;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getLanguage(): ArrayCollection
    {
        return $this->language;
    }

    /**
     * @param ArrayCollection $language
     *
     * @return DocumentInterface
     */
    public function setLanguage(ArrayCollection $language): DocumentInterface
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getSubtitle(): ArrayCollection
    {
        return $this->subtitle;
    }

    /**
     * @param ArrayCollection $subtitle
     *
     * @return DocumentInterface
     */
    public function setSubtitle(ArrayCollection $subtitle): DocumentInterface
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
     * @return ArrayCollection
     */
    public function getLogicalStructures(): ArrayCollection
    {
        return $this->logicalStructures;
    }

    /**
     * @param ArrayCollection $logicalStructures
     *
     * @return DocumentInterface
     */
    public function setLogicalStructures(ArrayCollection $logicalStructures): DocumentInterface
    {
        $this->logicalStructures = $logicalStructures;

        return $this;
    }

    /**
     * @param LogicalStructure $structure
     */
    public function addLogicalStructure(LogicalStructure $structure)
    {
        $this->logicalStructures->add($structure);
    }

    /**
     * @param int $id
     *
     * @return LogicalStructure
     */
    public function getLogicalStructure(int $id): LogicalStructure
    {
        return $this->logicalStructures->get($id);
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
     * @return ArrayCollection
     */
    public function getPhysicalStructures(): ArrayCollection
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
     * @param ArrayCollection $physicalStructures
     *
     * @return DocumentInterface
     */
    public function setPhysicalStructures(ArrayCollection $physicalStructures): DocumentInterface
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
     * @return ArrayCollection
     */
    public function getParents(): ArrayCollection
    {
        return $this->parents;
    }

    /**
     * @param ArrayCollection $parents
     *
     * @return DocumentInterface
     */
    public function setParents(ArrayCollection $parents): DocumentInterface
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
     * @return ArrayCollection
     */
    public function getRenderings(): ArrayCollection
    {
        return $this->renderings;
    }

    /**
     * @param ArrayCollection $renderings
     *
     * @return DocumentInterface
     */
    public function setRenderings(ArrayCollection $renderings): DocumentInterface
    {
        $this->renderings = $renderings;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getSeeAlso(): ArrayCollection
    {
        return $this->seeAlso;
    }

    /**
     * @param ArrayCollection $seeAlso
     *
     * @return DocumentInterface
     */
    public function setSeeAlso(ArrayCollection $seeAlso): DocumentInterface
    {
        $this->seeAlso = $seeAlso;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getMetadata(): ArrayCollection
    {
        return $this->metadata;
    }

    /**
     * @param ArrayCollection $metadata
     *
     * @return DocumentInterface
     */
    public function setMetadata(ArrayCollection $metadata): DocumentInterface
    {
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getAdditionalIdentifiers(): ArrayCollection
    {
        return $this->additionalIdentifiers;
    }

    /**
     * @param ArrayCollection $additionalIdentifiers
     *
     * @return DocumentInterface
     */
    public function setAdditionalIdentifiers(ArrayCollection $additionalIdentifiers): DocumentInterface
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
     * @return ArrayCollection
     */
    public function getRelated(): ArrayCollection
    {
        return $this->related;
    }

    /**
     * @param ArrayCollection $related
     *
     * @return DocumentInterface
     */
    public function setRelated(ArrayCollection $related): DocumentInterface
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
