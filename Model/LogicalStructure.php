<?php

declare(strict_types=1);

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
     * @var int
     */
    private $level;

    /**
     * @var array
     */
    private $renderings;

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
     * @return int
     */
    public function getEndPage(): int
    {
        return $this->endPage;
    }

    /**
     * @param int $endPage
     *
     * @return LogicalStructure
     */
    public function setEndPage(int $endPage): LogicalStructure
    {
        $this->endPage = $endPage;

        return $this;
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @param int $level
     *
     * @return LogicalStructure
     */
    public function setLevel(int $level): LogicalStructure
    {
        $this->level = $level;

        return $this;
    }

    /**
     * @return array
     */
    public function getRenderings(): array
    {
        return $this->renderings;
    }

    /**
     * @param array $renderings
     *
     * @return LogicalStructure
     */
    public function setRenderings(array $renderings): LogicalStructure
    {
        $this->renderings = $renderings;

        return $this;
    }
}
