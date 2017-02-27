<?php

declare(strict_types=1);

namespace Subugoe\IIIFBundle\Model\Presentation;

use JMS\Serializer\Annotation as Serializer;

/**
 * IIIF Service definition.
 *
 * @Serializer\ExclusionPolicy("NONE")
 */
class Service
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
     * @var string
     */
    private $profile = 'http://iiif.io/api/image/2/level1.json';

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
     * @return Service
     */
    public function setId(string $id): Service
    {
        $this->id = $id;

        return $this;
    }
}
