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
     * @var array
     */
    private $metadata;

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
    public function setId(string $id): self
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
    public function setLabel(string $label): self
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
    public function setType(string $type): self
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
    public function setStartPage(int $startPage): self
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
    public function setEndPage(int $endPage): self
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
    public function setLevel(int $level): self
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
    public function setRenderings(array $renderings): self
    {
        $this->renderings = $renderings;

        return $this;
    }

    /**
     * @return array
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }

    /**
     * @param array $metadata
     *
     * @return LogicalStructure
     */
    public function setMetadata(array $metadata): self
    {
        $this->metadata = $metadata;

        return $this;
    }
}
