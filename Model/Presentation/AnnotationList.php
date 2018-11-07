<?php

declare(strict_types=1);

namespace Subugoe\IIIFBundle\Model\Presentation;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;

/**
 * @see http://iiif.io/api/presentation/2.1/#annotation-list
 */
class AnnotationList
{
    /**
     * @var string
     * @Serializer\SerializedName("@context")
     * @Serializer\Since("2.0")
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
    private $type = 'sc:AnnotationList';

    /**
     * @var ArrayCollection
     */
    private $resources;

    /**
     * @return string
     */
    public function getContext(): string
    {
        return $this->context;
    }

    /**
     * @param string $context
     *
     * @return AnnotationList
     */
    public function setContext(string $context): self
    {
        $this->context = $context;

        return $this;
    }

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
     * @return AnnotationList
     */
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return AnnotationList
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getResources(): ArrayCollection
    {
        return $this->resources;
    }

    /**
     * @param ArrayCollection $resources
     *
     * @return AnnotationList
     */
    public function setResources(ArrayCollection $resources): self
    {
        $this->resources = $resources;

        return $this;
    }
}
