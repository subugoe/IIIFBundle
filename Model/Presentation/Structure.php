<?php

declare(strict_types=1);

namespace Subugoe\IIIFBundle\Model\Presentation;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;

/**
 * @see http://iiif.io/api/presentation/2.1/#range
 * @Serializer\ExclusionPolicy("NONE")
 */
class Structure
{
    /**
     * @var string
     * @Serializer\SerializedName("@id")
     */
    private $id;

    /**
     * @var string
     * @Serializer\SerializedName("@type")
     */
    private $type = 'sc:Range';

    /**
     * @var ArrayCollection
     */
    private $metadata;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     * @Serializer\SerializedName("viewingHint")
     */
    private $viewingHint;

    /**
     * @var ArrayCollection
     */
    private $members;

    /**
     * @var ArrayCollection
     */
    private $ranges;

    /**
     * @var ArrayCollection;
     */
    private $canvases;

    /**
     * @var string
     */
    private $within;

    /**
     * @var ArrayCollection
     * @Serializer\SkipWhenEmpty()
     */
    private $rendering;

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
     * @return Structure
     */
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
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
     * @return Structure
     */
    public function setType(string $type): self
    {
        $this->type = $type;

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
     * @return Structure
     */
    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return string
     */
    public function getViewingHint(): string
    {
        return $this->viewingHint;
    }

    /**
     * @param string $viewingHint
     *
     * @return Structure
     */
    public function setViewingHint(string $viewingHint): self
    {
        $this->viewingHint = $viewingHint;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getMembers(): ArrayCollection
    {
        return $this->members;
    }

    /**
     * @param ArrayCollection $members
     *
     * @return Structure
     */
    public function setMembers(ArrayCollection $members): self
    {
        $this->members = $members;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getRanges(): ArrayCollection
    {
        return $this->ranges;
    }

    /**
     * @param ArrayCollection $ranges
     *
     * @return Structure
     */
    public function setRanges(ArrayCollection $ranges): self
    {
        $this->ranges = $ranges;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getCanvases(): ArrayCollection
    {
        return $this->canvases;
    }

    /**
     * @param ArrayCollection $canvases
     *
     * @return Structure
     */
    public function setCanvases(ArrayCollection $canvases): self
    {
        $this->canvases = $canvases;

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
     * @return Structure
     */
    public function setWithin(string $within): self
    {
        $this->within = $within;

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
     * @return Structure
     */
    public function setMetadata(ArrayCollection $metadata): self
    {
        $this->metadata = $metadata;

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
     * @return Structure
     */
    public function setRendering(ArrayCollection $rendering): self
    {
        $this->rendering = $rendering;

        return $this;
    }
}
