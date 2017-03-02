<?php

namespace Subugoe\IIIFBundle\Model;

/**
 * Logical structure.
 */
class LogicalStructure
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $type;

    /**
     * @var int
     */
    private $startPage;

    /**
     * @var int
     */
    private $endPage;

    /**
     * @var string
     */
    private $level;

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
     * @return LogicalStructure
     */
    public function setId(string $id): LogicalStructure
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
     * @return LogicalStructure
     */
    public function setLabel(string $label): LogicalStructure
    {
        $this->label = $label;

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
     * @return LogicalStructure
     */
    public function setType(string $type): LogicalStructure
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return int
     */
    public function getStartPage(): int
    {
        return $this->startPage;
    }

    /**
     * @param int $startPage
     *
     * @return LogicalStructure
     */
    public function setStartPage(int $startPage): LogicalStructure
    {
        $this->startPage = $startPage;

        return $this;
    }

    /**
     * @return string
     */
    public function getEndPage(): string
    {
        return $this->endPage;
    }

    /**
     * @param string $endPage
     *
     * @return LogicalStructure
     */
    public function setEndPage(string $endPage): LogicalStructure
    {
        $this->endPage = $endPage;

        return $this;
    }

    /**
     * @return string
     */
    public function getLevel(): string
    {
        return $this->level;
    }

    /**
     * @param string $level
     *
     * @return LogicalStructure
     */
    public function setLevel(string $level): LogicalStructure
    {
        $this->level = $level;

        return $this;
    }
}
