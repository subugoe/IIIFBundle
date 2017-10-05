<?php

declare(strict_types=1);

namespace Subugoe\IIIFBundle\Model\Presentation;

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
     * @var array
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
     * @var array
     */
    private $members;

    /**
     * @var array
     */
    private $ranges;

    /**
     * @var array;
     */
    private $canvases;

    /**
     * @var string
     */
    private $within;

    /**
     * @var array
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
    public function setId(string $id): Structure
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
    public function setType(string $type): Structure
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
    public function setLabel(string $label): Structure
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
    public function setViewingHint(string $viewingHint): Structure
    {
        $this->viewingHint = $viewingHint;

        return $this;
    }

    /**
     * @return array
     */
    public function getMembers(): array
    {
        return $this->members;
    }

    /**
     * @param array $members
     *
     * @return Structure
     */
    public function setMembers(array $members): Structure
    {
        $this->members = $members;

        return $this;
    }

    /**
     * @return array
     */
    public function getRanges(): array
    {
        return $this->ranges;
    }

    /**
     * @param array $ranges
     *
     * @return Structure
     */
    public function setRanges(array $ranges): Structure
    {
        $this->ranges = $ranges;

        return $this;
    }

    /**
     * @return array
     */
    public function getCanvases(): array
    {
        return $this->canvases;
    }

    /**
     * @param array $canvases
     *
     * @return Structure
     */
    public function setCanvases(array $canvases): Structure
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
    public function setWithin(string $within): Structure
    {
        $this->within = $within;

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
     * @return Structure
     */
    public function setMetadata(array $metadata): Structure
    {
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * @return array
     */
    public function getRendering(): array
    {
        return $this->rendering;
    }

    /**
     * @param array $rendering
     *
     * @return Structure
     */
    public function setRendering(array $rendering): Structure
    {
        $this->rendering = $rendering;

        return $this;
    }
}
