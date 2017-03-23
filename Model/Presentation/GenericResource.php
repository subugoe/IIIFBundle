<?php

declare(strict_types=1);

namespace Subugoe\IIIFBundle\Model\Presentation;

use JMS\Serializer\Annotation as Serializer;

/**
 * @see http://iiif.io/api/presentation/2.1/#image-resources
 *
 * @Serializer\ExclusionPolicy("NONE")
 */
class GenericResource
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
     * @return GenericResource
     */
    public function setId(string $id): GenericResource
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
     * @return GenericResource
     */
    public function setResource(ResourceData $resource): GenericResource
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
     * @return GenericResource
     */
    public function setHeight(int $height): GenericResource
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
     * @return GenericResource
     */
    public function setWidth(int $width): GenericResource
    {
        $this->width = $width;

        return $this;
    }
}
