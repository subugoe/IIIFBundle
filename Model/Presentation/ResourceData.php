<?php

declare(strict_types=1);

namespace Subugoe\IIIFBundle\Model\Presentation;

use JMS\Serializer\Annotation as Serializer;

/**
 * @see http://iiif.io/api/presentation/2.1/#image-resources
 */
class ResourceData
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
    private $type = 'dctypes:Image';

    /**
     * @var string
     */
    private $format;

    /**
     * @var Service
     */
    private $service;

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
     * @return ResourceData
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @param string $format
     *
     * @return ResourceData
     */
    public function setFormat(string $format): ResourceData
    {
        $this->format = $format;

        return $this;
    }

    /**
     * @return Service
     */
    public function getService(): Service
    {
        return $this->service;
    }

    /**
     * @param Service $service
     *
     * @return ResourceData
     */
    public function setService(Service $service): ResourceData
    {
        $this->service = $service;

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
     * @return ResourceData
     */
    public function setHeight(int $height): ResourceData
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
     * @return ResourceData
     */
    public function setWidth(int $width): ResourceData
    {
        $this->width = $width;

        return $this;
    }
}
