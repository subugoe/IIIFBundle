<?php

declare(strict_types=1);

namespace Subugoe\IIIFBundle\Model\Presentation;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as Serializer;

class Collection
{
    /**
     * @var string
     * @Serializer\SerializedName("@id")
     */
    private $id;

    /**
     * @var string
     * @Serializer\SerializedName("@context")
     */
    private $context = 'http://iiif.io/api/presentation/2/context.json';

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
     * @var ArrayCollection
     */
    private $medatata;

    /**
     * @var string
     */
    private $description;

    /**
     * @var Image
     */
    private $thumbnail;

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
     * @return Collection
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
     * @return Collection
     */
    public function setType(string $type): self
    {
        $this->type = $type;

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
     * @return Collection
     */
    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getMedatata(): ArrayCollection
    {
        return $this->medatata;
    }

    /**
     * @param ArrayCollection $medatata
     *
     * @return Collection
     */
    public function setMedatata(ArrayCollection $medatata): self
    {
        $this->medatata = $medatata;

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
     * @return Collection
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Image
     */
    public function getThumbnail(): Image
    {
        return $this->thumbnail;
    }

    /**
     * @param Image $thumbnail
     *
     * @return Collection
     */
    public function setThumbnail(Image $thumbnail): self
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }
}
