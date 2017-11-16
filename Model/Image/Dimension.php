<?php

declare(strict_types=1);

namespace Subugoe\IIIFBundle\Model\Image;

use JMS\Serializer\Annotation as Serializer;

/**
 * Image Dimensions.
 *
 * @Serializer\ExclusionPolicy("NONE")
 */
class Dimension
{
    /**
     * @var float
     */
    private $height;

    /**
     * @var float
     */
    private $width;

    /**
     * @return float
     */
    public function getHeight(): float
    {
        return $this->height;
    }

    /**
     * @param float $height
     *
     * @return Dimension
     */
    public function setHeight(float $height): self
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @return float
     */
    public function getWidth(): float
    {
        return $this->width;
    }

    /**
     * @param float $width
     *
     * @return Dimension
     */
    public function setWidth(float $width): self
    {
        $this->width = $width;

        return $this;
    }
}
