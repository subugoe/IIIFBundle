<?php

namespace Subugoe\IIIFBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Document for holding generic data.
 */
interface DocumentInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @param string $id
     *
     * @return DocumentInterface
     */
    public function setId(string $id): DocumentInterface;

    /**
     * @return ArrayCollection
     */
    public function getTitle(): ArrayCollection;

    /**
     * @param ArrayCollection $title
     *
     * @return DocumentInterface
     */
    public function setTitle(ArrayCollection $title): DocumentInterface;

    /**
     * @return ArrayCollection
     */
    public function getRightsOwner(): ArrayCollection;

    /**
     * @param ArrayCollection $rightsOwner
     *
     * @return DocumentInterface
     */
    public function setRightsOwner(ArrayCollection $rightsOwner): DocumentInterface;

    /**
     * @return ArrayCollection
     */
    public function getAuthors(): ArrayCollection;

    /**
     * @param ArrayCollection $authors
     *
     * @return DocumentInterface
     */
    public function setAuthors(ArrayCollection $authors): DocumentInterface;

    /**
     * @return ArrayCollection
     */
    public function getPublishingPlaces(): ArrayCollection;

    /**
     * @param ArrayCollection $publishingPlaces
     *
     * @return DocumentInterface
     */
    public function setPublishingPlaces(ArrayCollection $publishingPlaces): DocumentInterface;

    /**
     * @return ArrayCollection
     */
    public function getClassification(): ArrayCollection;

    /**
     * @param ArrayCollection $classification
     *
     * @return DocumentInterface
     */
    public function setClassification(ArrayCollection $classification): DocumentInterface;

    /**
     * @return int
     */
    public function getPublishingYear(): int;

    /**
     * @param int $publishingYear
     *
     * @return DocumentInterface
     */
    public function setPublishingYear(int $publishingYear): DocumentInterface;

    /**
     * @return ArrayCollection
     */
    public function getPublisher(): ArrayCollection;

    /**
     * @param ArrayCollection $publisher
     *
     * @return DocumentInterface
     */
    public function setPublisher(ArrayCollection $publisher): DocumentInterface;

    /**
     * @return ArrayCollection
     */
    public function getLanguage(): ArrayCollection;

    /**
     * @param ArrayCollection $language
     *
     * @return DocumentInterface
     */
    public function setLanguage(ArrayCollection $language): DocumentInterface;

    /**
     * @return ArrayCollection
     */
    public function getSubtitle(): ArrayCollection;

    /**
     * @param ArrayCollection $subtitle
     *
     * @return DocumentInterface
     */
    public function setSubtitle(ArrayCollection $subtitle): DocumentInterface;

    /**
     * @return string
     */
    public function getImageFormat(): string;

    /**
     * @param string $imageFormat
     *
     * @return DocumentInterface
     */
    public function setImageFormat(string $imageFormat): DocumentInterface;

    /**
     * @return ArrayCollection
     */
    public function getLogicalStructures(): ArrayCollection;

    /**
     * @param ArrayCollection $logicalStructures
     *
     * @return DocumentInterface
     */
    public function setLogicalStructures(ArrayCollection $logicalStructures): DocumentInterface;

    /**
     * @param LogicalStructure $structure
     */
    public function addLogicalStructure(LogicalStructure $structure);

    /**
     * @param int $id
     *
     * @return LogicalStructure
     */
    public function getLogicalStructure(int $id): LogicalStructure;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @param string $type
     *
     * @return DocumentInterface
     */
    public function setType(string $type): DocumentInterface;

    /**
     * @return ArrayCollection
     */
    public function getPhysicalStructures(): ArrayCollection;

    /**
     * @param int $id
     *
     * @return PhysicalStructure
     */
    public function getPhysicalStructure(int $id): PhysicalStructure;

    /**
     * @param ArrayCollection $physicalStructures
     *
     * @return DocumentInterface
     */
    public function setPhysicalStructures(ArrayCollection $physicalStructures): DocumentInterface;

    /**
     * @param PhysicalStructure $structure
     */
    public function addPhysicalStructure(PhysicalStructure $structure);

    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * @param string $description
     *
     * @return DocumentInterface
     */
    public function setDescription(string $description): DocumentInterface;

    /**
     * @return ArrayCollection
     */
    public function getParents(): ArrayCollection;

    /**
     * @param ArrayCollection $parents
     *
     * @return DocumentInterface
     */
    public function setParents(ArrayCollection $parents): DocumentInterface;

    /**
     * @param DocumentInterface $document
     */
    public function setParent(DocumentInterface $document);

    /**
     * @return ArrayCollection
     */
    public function getRenderings(): ArrayCollection;

    /**
     * @param ArrayCollection $renderings
     *
     * @return DocumentInterface
     */
    public function setRenderings(ArrayCollection $renderings): DocumentInterface;

    /**
     * @return ArrayCollection
     */
    public function getSeeAlso(): ArrayCollection;

    /**
     * @param ArrayCollection $seeAlso
     *
     * @return DocumentInterface
     */
    public function setSeeAlso(ArrayCollection $seeAlso): DocumentInterface;

    /**
     * @return ArrayCollection
     */
    public function getMetadata(): ArrayCollection;

    /**
     * @param ArrayCollection $metadata
     *
     * @return DocumentInterface
     */
    public function setMetadata(ArrayCollection $metadata): DocumentInterface;

    /**
     * @return ArrayCollection
     */
    public function getAdditionalIdentifiers(): ArrayCollection;

    /**
     * @param ArrayCollection $additionalIdentifiers
     *
     * @return DocumentInterface
     */
    public function setAdditionalIdentifiers(ArrayCollection $additionalIdentifiers): DocumentInterface;

    /**
     * @param string $key
     * @param string $value
     */
    public function addMetadata(string $key, string $value);

    /**
     * @return ArrayCollection
     */
    public function getRelated(): ArrayCollection;

    /**
     * @param ArrayCollection $related
     *
     * @return DocumentInterface
     */
    public function setRelated(ArrayCollection $related): DocumentInterface;

    /**
     * @return \DateTimeImmutable
     */
    public function getIndexingDate(): \DateTimeImmutable;

    /**
     * @param \DateTimeImmutable $indexingDate
     *
     * @return DocumentInterface
     */
    public function setIndexingDate(\DateTimeImmutable $indexingDate): DocumentInterface;

    /**
     * @return string
     */
    public function getTitlePage(): string;

    /**
     * @param string $titlePage
     *
     * @return DocumentInterface
     */
    public function setTitlePage(string $titlePage): DocumentInterface;

    /**
     * @return string
     */
    public function getIssue(): string;

    /**
     * @param string $issue
     *
     * @return DocumentInterface
     */
    public function setIssue(string $issue): DocumentInterface;

    /**
     * @return string
     */
    public function getPermaLink(): string;

    /**
     * @param string $permaLink
     *
     * @return DocumentInterface
     */
    public function setPermaLink(string $permaLink): DocumentInterface;

    /**
     * @return string
     */
    public function getLicense(): string;

    /**
     * @param string $license
     *
     * @return DocumentInterface
     */
    public function setLicense(string $license): DocumentInterface;
}
