<?php

declare(strict_types=1);

namespace Subugoe\IIIFBundle\Model\Image;

use JMS\Serializer\Annotation as Serializer;

/**
 * Image informations for info.json file.
 *
 * @Serializer\ExclusionPolicy("NONE")
 */
class ImageInformation
{
    /**
     * @var string
     * @Serializer\SerializedName("@context")
     */
    private $context = 'http://iiif.io/api/image/2/context.json';

    /**
     * @var string
     * @Serializer\SerializedName("@id")
     */
    private $id;

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
     */
    private $ppi;

    /**
     * @var string
     */
    private $protocol = 'http://iiif.io/api/image';

    /**
     * @var string
     */
    private $profile = 'http://iiif.io/api/image/2/level0.json';

    /**
     * @var array
     */
    private $sizes;

    /**
     * @var array
     */
    private $tiles;

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
     * @return ImageInformation
     */
    public function setId(string $id): self
    {
        $this->id = $id;

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
     * @return ImageInformation
     */
    public function setWidth(int $width): self
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
     * @return ImageInformation
     */
    public function setHeight(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @return array
     */
    public function getPpi(): array
    {
        return $this->ppi;
    }

    /**
     * @param array $ppi
     *
     * @return ImageInformation
     */
    public function setPpi(array $ppi): self
    {
        $this->ppi = $ppi;

        return $this;
    }

    /**
     * @return array
     */
    public function getSizes(): array
    {
        return $this->sizes;
    }

    /**
     * @param array $sizes
     *
     * @return ImageInformation
     */
    public function setSizes(array $sizes): self
    {
        $this->sizes = $sizes;

        return $this;
    }

    /**
     * @return array
     */
    public function getTiles(): array
    {
        return $this->tiles;
    }

    /**
     * @param array $tiles
     *
     * @return ImageInformation
     */
    public function setTiles(array $tiles): self
    {
        $this->tiles = $tiles;

        return $this;
    }
}
