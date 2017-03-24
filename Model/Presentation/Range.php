<?php

declare(strict_types=1);

namespace Subugoe\IIIFBundle\Model\Presentation;

use JMS\Serializer\Annotation as Serializer;

/**
 * @see http://iiif.io/api/presentation/2.1/#range
 * Range
 */
class Range
{
    /**
     * @Serializer\SerializedName("@id")
     *
     * @var string
     */
    private $id;

    /**
     * @Serializer\SerializedName("@type")
     *
     * @var string
     */
    private $type = 'sc:Range';

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     * @Serializer\SerializedName("viewingHint")
     */
    private $viewingHint = 'top';

    /**
     * @var array
     */
    private $members;

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
     * @return Range
     */
    public function setId(string $id): Range
    {
        $this->id = $id;

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
     * @return Range
     */
    public function setLabel(string $label): Range
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
     * @return Range
     */
    public function setViewingHint(string $viewingHint): Range
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
     * @return Range
     */
    public function setMembers(array $members): Range
    {
        $this->members = $members;

        return $this;
    }

    /**
     * @param Canvas $canvas
     */
    public function addMember(Canvas $canvas)
    {
        $this->members[] = $canvas;
    }
}
