<?php

declare(strict_types=1);

namespace Subugoe\IIIFBundle\Model\Presentation;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class Canvas.
 *
 * @see http://iiif.io/api/presentation/2.1/#canvas
 *
 * @Serializer\ExclusionPolicy("NONE")
 */
class Canvas
{
    /**
     * @var string
     * @Serializer\SerializedName("@context")
     */
    private $context = 'http://iiif.io/api/presentation/2/context.json';

    /**
     * @var string
     * @Serializer\SerializedName("@id")
     */
    private $id;

    /**
     * @var string
     * @Serializer\SerializedName("@type")
     */
    private $type = 'sc:Canvas';

    /**
     * @var string
     */
    private $label;

    /**
     * @var int
     */
    private $height;

    /**
     * @var int
     */
    private $width;

    /**
     * @var array
     */
    private $images;

    /**
     * @var array
     * @Serializer\SerializedName("otherContent")
     */
    private $otherContent;

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
     * @return Canvas
     */
    public function setId(string $id): Canvas
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
     * @return Canvas
     */
    public function setLabel(string $label): Canvas
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @param int $height
     *
     * @return Canvas
     */
    public function setHeight(int $height): Canvas
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @param int $width
     *
     * @return Canvas
     */
    public function setWidth(int $width): Canvas
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @return array
     */
    public function getImages(): array
    {
        return $this->images;
    }

    /**
     * @param array $images
     *
     * @return Canvas
     */
    public function setImages(array $images): Canvas
    {
        $this->images = $images;

        return $this;
    }

    /**
     * @return array
     */
    public function getOtherContent(): array
    {
        return $this->otherContent;
    }

    /**
     * @param array $otherContent
     *
     * @return Canvas
     */
    public function setOtherContent(array $otherContent): Canvas
    {
        $this->otherContent = $otherContent;

        return $this;
    }
}
