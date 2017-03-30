<?php

declare(strict_types=1);

namespace Subugoe\IIIFBundle\Model\Presentation;

use JMS\Serializer\Annotation as Serializer;

/**
 * Top Collection. Features all available collections.
 */
class Collections
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
    private $type = 'sc:Collection';

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     * @Serializer\SerializedName("viewingHint")
     */
    private $viewingHint = 'top';

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $attribution;

    /**
     * @var array
     */
    private $collections;

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
     * @return Collections
     */
    public function setId(string $id): Collections
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     *
     * @return Collections
     */
    public function setLabel(string $label): Collections
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return Collections
     */
    public function setDescription(string $description): Collections
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getAttribution(): string
    {
        return $this->attribution;
    }

    /**
     * @param string $attribution
     *
     * @return Collections
     */
    public function setAttribution(string $attribution): Collections
    {
        $this->attribution = $attribution;

        return $this;
    }

    /**
     * @return array
     */
    public function getCollections(): array
    {
        return $this->collections;
    }

    /**
     * @param array $collections
     *
     * @return Collections
     */
    public function setCollections(array $collections): Collections
    {
        $this->collections = $collections;

        return $this;
    }

    /**
     * @param Collection $collection
     */
    public function addCollection(Collection $collection)
    {
        $this->collections[] = $collection;
    }
}
