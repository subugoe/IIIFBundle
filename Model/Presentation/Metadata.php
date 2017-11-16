<?php

declare(strict_types=1);

namespace Subugoe\IIIFBundle\Model\Presentation;

use JMS\Serializer\Annotation as Serializer;

/**
 * Manifest metadata.
 *
 * @Serializer\ExclusionPolicy("NONE")
 */
class Metadata
{
    /**
     * @var string
     */
    private $label;

    /**
     * @var string|array
     */
    private $value;

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
     * @return Metadata
     */
    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return string|array
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string|array $value
     *
     * @return Metadata
     */
    public function setValue($value): self
    {
        $this->value = $value;

        return $this;
    }
}
