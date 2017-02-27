<?php

declare(strict_types=1);

namespace Subugoe\IIIFBundle\Model\Presentation;

use JMS\Serializer\Annotation as Serializer;

/**
 * @see http://iiif.io/api/presentation/2.1/#image-resources
 *
 * @Serializer\ExclusionPolicy("NONE")
 */
class ImageResource
{
    /**
     * @var string
     */
    private $context = 'http://iiif.io/api/presentation/2/context.json';

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $type = 'oa:Annotation';

    /**
     * @var string
     */
    private $motivation = 'sc:painting';

    /**
     * @var ResourceData
     */
    private $resource;

    /**
     * @var int
     */
    private $height;

    /**
     * @var int
     */
    private $width;

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
     * @return ImageResource
     */
    public function setId(string $id): ImageResource
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return ResourceData
     */
    public function getResource(): ResourceData
    {
        return $this->resource;
    }

    /**
     * @param ResourceData $resource
     *
     * @return ImageResource
     */
    public function setResource(ResourceData $resource): ImageResource
    {
        $this->resource = $resource;

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
     * @return ImageResource
     */
    public function setHeight(int $height): ImageResource
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
     * @return ImageResource
     */
    public function setWidth(int $width): ImageResource
    {
        $this->width = $width;

        return $this;
    }
}
