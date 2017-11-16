<?php

declare(strict_types=1);

namespace Subugoe\IIIFBundle\Model\Presentation;

use JMS\Serializer\Annotation as Serializer;

/**
 * "See also" section in manifests.
 */
class SeeAlso
{
    /**
     * @var string
     * @Serializer\SerializedName("@id")
     */
    private $id;

    /**
     * @var string
     */
    private $format;

    /**
     * @var string
     */
    private $profile;

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
     * @return SeeAlso
     */
    public function setId(string $id): self
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
     * @return SeeAlso
     */
    public function setFormat(string $format): self
    {
        $this->format = $format;

        return $this;
    }

    /**
     * @return string
     */
    public function getProfile(): string
    {
        return $this->profile;
    }

    /**
     * @param string $profile
     *
     * @return SeeAlso
     */
    public function setProfile(string $profile): self
    {
        $this->profile = $profile;

        return $this;
    }
}
