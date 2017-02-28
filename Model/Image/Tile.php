<?php

declare(strict_types=1);

namespace Subugoe\IIIFBundle\Model\Image;

use JMS\Serializer\Annotation as Serializer;

/**
 * Tiles for image api.
 *
 * @Serializer\ExclusionPolicy("NONE")
 */
class Tile
{
    /**
     * @var int
     */
    private $width;

    /**
     * @var int
     */
    private $height;

    /**
     * @var array
     * @Serializer\SerializedName("scaleFactors")
     */
    private $scaleFactors = [1, 2, 4, 8, 16];

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
     * @return Tile
     */
    public function setWidth(int $width): Tile
    {
        $this->width = $width;

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
     * @return Tile
     */
    public function setHeight(int $height): Tile
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @return array
     */
    public function getScaleFactors(): array
    {
        return $this->scaleFactors;
    }

    /**
     * @param array $scaleFactors
     *
     * @return Tile
     */
    public function setScaleFactors(array $scaleFactors): Tile
    {
        $this->scaleFactors = $scaleFactors;

        return $this;
    }
}
