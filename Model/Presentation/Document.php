<?php

declare(strict_types=1);

namespace Subugoe\IIIFBundle\Model\Presentation;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;

/**
 * A solr document.
 *
 * @see http://iiif.io/api/presentation/2.1/#manifest
 *
 * @Serializer\ExclusionPolicy("NONE")
 */
class Document
{
    /**
     * @var string
     * @Serializer\SerializedName("@id")
     * @Serializer\Since("v1")
     */
    private $id;

    /**
     * @var string
     * @Serializer\SerializedName("@type")
     * @Serializer\Since("v1")
     */
    private $type = 'sc:Manifest';

    /**
     * @var string
     * @Serializer\SerializedName("@context")
     * @Serializer\Since("v1")
     */
    private $context = 'http://iiif.io/api/presentation/2/context.json';

    /**
     * @var string
     * @Serializer\SerializedName("viewingDirection")
     */
    private $viewingDirection = 'left-to-right';

    /**
     * @var string
     * @Serializer\SerializedName("viewingHint")
     */
    private $viewingHint = 'paged';

    /**
     * @var string
     */
    private $license;

    /**
     * @var string
     */
    private $description;

    /**
     * @var \DateTime
     * @Serializer\SerializedName("navDate")
     */
    private $navDate;

    /**
     * @var string
     */
    private $within;

    /**
     * @var string
     */
    private $label;

    /**
     * @var ArrayCollection
     */
    private $metadata;

    /**
     * @var string
     */
    private $docstrct;

    /**
     * @var ArrayCollection
     */
    private $titles;

    /**
     * @var ArrayCollection
     */
    private $publishingPlaces;

    /**
     * @var Image
     */
    private $thumbnail;

    /**
     * @var ArrayCollection
     */
    private $sequences;

    /**
     * @var string
     * @Serializer\SkipWhenEmpty()
     */
    private $attribution;

    /**
     * @var Image
     */
    private $logo;

    /**
     * @var ArrayCollection
     * @Serializer\SkipWhenEmpty()
     */
    private $rendering;

    /**
     * @var ArrayCollection
     * @Serializer\SerializedName("seeAlso")
     */
    private $seeAlso;

    /**
     * A.k.a Ranges.
     *
     * @var ArrayCollection
     */
    private $structures;

    /**
     * @var ArrayCollection
     * @Serializer\SkipWhenEmpty()
     */
    private $related;

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
     * @return string
     */
    public function getDocstrct(): string
    {
        return $this->docstrct;
    }

    /**
     * @param string $docstrct
     *
     * @return Document
     */
    public function setDocstrct(string $docstrct): self
    {
        $this->docstrct = $docstrct;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getTitles(): ArrayCollection
    {
        return $this->titles;
    }

    /**
     * @param ArrayCollection $titles
     *
     * @return Document
     */
    public function setTitles(ArrayCollection $titles): self
    {
        $this->titles = $titles;

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
     * @return Document
     */
    public function setPublishingPlaces(ArrayCollection $publishingPlaces): self
    {
        $this->publishingPlaces = $publishingPlaces;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     *
     * @return Document
     */
    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Image
     */
    public function getThumbnail(): Image
    {
        return $this->thumbnail;
    }

    /**
     * @param Image $thumbnail
     *
     * @return Document
     */
    public function setThumbnail(Image $thumbnail): self
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getSequences(): ArrayCollection
    {
        return $this->sequences;
    }

    /**
     * @param ArrayCollection $sequences
     *
     * @return Document
     */
    public function setSequences(ArrayCollection $sequences): self
    {
        $this->sequences = $sequences;

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
     * @return Document
     */
    public function setMetadata(ArrayCollection $metadata): self
    {
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * @return string
     */
    public function getAttribution(): string
    {
        return $this->attribution;
    }

    /**
     * @param string $attribution
     *
     * @return Document
     */
    public function setAttribution(string $attribution): self
    {
        $this->attribution = $attribution;

        return $this;
    }

    /**
     * @return Image
     */
    public function getLogo(): Image
    {
        return $this->logo;
    }

    /**
     * @param Image $logo
     *
     * @return Document
     */
    public function setLogo(Image $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getStructures(): ArrayCollection
    {
        return $this->structures;
    }

    /**
     * @param ArrayCollection $structures
     *
     * @return Document
     */
    public function setStructures(ArrayCollection $structures): self
    {
        $this->structures = $structures;

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
     * @return Document
     */
    public function setLicense(string $license): self
    {
        $this->license = $license;

        return $this;
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
     * @return \DateTime
     */
    public function getNavDate(): \DateTime
    {
        return $this->navDate;
    }

    /**
     * @param \DateTime $navDate
     *
     * @return Document
     */
    public function setNavDate(\DateTime $navDate): self
    {
        $this->navDate = $navDate;

        return $this;
    }

    /**
     * @return string
     */
    public function getWithin(): string
    {
        return $this->within;
    }

    /**
     * @param string $within
     *
     * @return Document
     */
    public function setWithin(string $within): self
    {
        $this->within = $within;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getRendering(): ArrayCollection
    {
        return $this->rendering;
    }

    /**
     * @param ArrayCollection $rendering
     *
     * @return Document
     */
    public function setRendering(ArrayCollection $rendering): self
    {
        $this->rendering = $rendering;

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
     * @return Document
     */
    public function setSeeAlso(ArrayCollection $seeAlso): self
    {
        $this->seeAlso = $seeAlso;

        return $this;
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
     * @return Document
     */
    public function setRelated(ArrayCollection $related): self
    {
        $this->related = $related;

        return $this;
    }
}
