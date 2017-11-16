<?php

namespace Subugoe\IIIFBundle\Model;

/**
 * Describes the physical structure of a work (scanned pages).
 */
class PhysicalStructure
{
    /**
     * @var int
     */
    private $order;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $identifier;

    /**
     * @var string
     */
    private $page;

    /**
     * @var string
     */
    private $filename;

    /**
     * @var string
     */
    private $annotation;

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return $this->order;
    }

    /**
     * @param int $order
     *
     * @return PhysicalStructure
     */
    public function setOrder(int $order): self
    {
        $this->order = $order;

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
     * @return PhysicalStructure
     */
    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     *
     * @return PhysicalStructure
     */
    public function setIdentifier(string $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * @return string
     */
    public function getPage(): string
    {
        return $this->page;
    }

    /**
     * @param string $page
     *
     * @return PhysicalStructure
     */
    public function setPage(string $page): self
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     *
     * @return PhysicalStructure
     */
    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * @return string
     */
    public function getAnnotation(): string
    {
        return $this->annotation;
    }

    /**
     * @param string $annotation
     *
     * @return PhysicalStructure
     */
    public function setAnnotation(string $annotation): self
    {
        $this->annotation = $annotation;

        return $this;
    }
}
