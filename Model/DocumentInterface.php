<?php

namespace Subugoe\IIIFBundle\Model;

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
     * @return array
     */
    public function getTitle(): array;

    /**
     * @param array $title
     *
     * @return DocumentInterface
     */
    public function setTitle(array $title): DocumentInterface;

    /**
     * @return array
     */
    public function getRightsOwner(): array;

    /**
     * @param array $rightsOwner
     *
     * @return DocumentInterface
     */
    public function setRightsOwner(array $rightsOwner): DocumentInterface;

    /**
     * @return array
     */
    public function getAuthors(): array;

    /**
     * @param array $authors
     *
     * @return DocumentInterface
     */
    public function setAuthors(array $authors): DocumentInterface;

    /**
     * @return array
     */
    public function getPublishingPlaces(): array;

    /**
     * @param array $publishingPlaces
     *
     * @return DocumentInterface
     */
    public function setPublishingPlaces(array $publishingPlaces): DocumentInterface;

    /**
     * @return array
     */
    public function getClassification(): array;

    /**
     * @param array $classification
     *
     * @return DocumentInterface
     */
    public function setClassification(array $classification): DocumentInterface;

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
     * @return array
     */
    public function getPublisher(): array;

    /**
     * @param array $publisher
     *
     * @return DocumentInterface
     */
    public function setPublisher(array $publisher): DocumentInterface;

    /**
     * @return array
     */
    public function getLanguage(): array;

    /**
     * @param array $language
     *
     * @return DocumentInterface
     */
    public function setLanguage(array $language): DocumentInterface;

    /**
     * @return array
     */
    public function getSubtitle(): array;

    /**
     * @param array $subtitle
     *
     * @return DocumentInterface
     */
    public function setSubtitle(array $subtitle): DocumentInterface;

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
     * @return array
     */
    public function getLogicalStructures(): array;

    /**
     * @param array $logicalStructures
     *
     * @return DocumentInterface
     */
    public function setLogicalStructures(array $logicalStructures): DocumentInterface;

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
     * @return array
     */
    public function getPhysicalStructures(): array;

    /**
     * @param int $id
     *
     * @return PhysicalStructure
     */
    public function getPhysicalStructure(int $id): PhysicalStructure;

    /**
     * @param array $physicalStructures
     *
     * @return DocumentInterface
     */
    public function setPhysicalStructures(array $physicalStructures): DocumentInterface;

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
     * @return array
     */
    public function getParents(): array;

    /**
     * @param array $parents
     *
     * @return DocumentInterface
     */
    public function setParents(array $parents): DocumentInterface;

    /**
     * @param DocumentInterface $document
     */
    public function setParent(DocumentInterface $document);

    /**
     * @return array
     */
    public function getRenderings(): array;

    /**
     * @param array $renderings
     *
     * @return DocumentInterface
     */
    public function setRenderings(array $renderings): DocumentInterface;

    /**
     * @return array
     */
    public function getSeeAlso(): array;

    /**
     * @param array $seeAlso
     *
     * @return DocumentInterface
     */
    public function setSeeAlso(array $seeAlso): DocumentInterface;

    /**
     * @return array
     */
    public function getMetadata(): array;

    /**
     * @param array $metadata
     *
     * @return DocumentInterface
     */
    public function setMetadata(array $metadata): DocumentInterface;

    /**
     * @return array
     */
    public function getAdditionalIdentifiers(): array;

    /**
     * @param array $additionalIdentifiers
     *
     * @return DocumentInterface
     */
    public function setAdditionalIdentifiers(array $additionalIdentifiers): DocumentInterface;

    /**
     * @param string $key
     * @param string $value
     */
    public function addMetadata(string $key, string $value);

    /**
     * @return array
     */
    public function getRelated(): array;

    /**
     * @param array $related
     *
     * @return DocumentInterface
     */
    public function setRelated(array $related): DocumentInterface;

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
