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
    public function setOrder(int $order): PhysicalStructure
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
    public function setLabel(string $label): PhysicalStructure
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
    public function setIdentifier(string $identifier): PhysicalStructure
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
    public function setPage(string $page): PhysicalStructure
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
    public function setFilename(string $filename): PhysicalStructure
    {
        $this->filename = $filename;

        return $this;
    }
}
