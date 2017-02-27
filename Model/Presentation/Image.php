<?php

declare(strict_types=1);

namespace Subugoe\IIIFBundle\Model\Presentation;

use JMS\Serializer\Annotation as Serializer;

/**
 * An image.
 *
 * @Serializer\ExclusionPolicy("NONE")
 */
class Image
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var Service
     */
    private $service;

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
     * @return Image
     */
    public function setId(string $id): Image
    {
        $this->id = $id;

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
     * @return Image
     */
    public function setService(Service $service): Image
    {
        $this->service = $service;

        return $this;
    }
}
