<?php

declare(strict_types=1);

namespace Subugoe\IIIFBundle\Model\Presentation;

use JMS\Serializer\Annotation as Serializer;

/**
 * A sequence.
 *
 * @see http://iiif.io/api/presentation/2.1/#sequence
 *
 * @Serializer\ExclusionPolicy("NONE")
 */
final class Sequence
{
    /**
     * @var string
     * @Serializer\SerializedName("@context")
     * @Serializer\Exclude(if="object.getContext() === ''")
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
    private $type = 'sc:Sequence';

    /**
     * @var string
     */
    private $label = 'Current Page Order';

    /**
     * @var string
     * @Serializer\SerializedName("viewingDirection")
     */
    private $viewingDirection = 'left-to-right';

    /**
     * @var string
     * @Serializer\SerializedName("viewingHint")
     */
    private $viewingHint = 'paged';

    /**
     * @var string
     * @Serializer\SerializedName("startCanvas")
     */
    private $startCanvas;

    /**
     * @var array
     */
    private $canvases;

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
     * @return Sequence
     */
    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getStartCanvas(): string
    {
        return $this->startCanvas;
    }

    /**
     * @param string $startCanvas
     *
     * @return Sequence
     */
    public function setStartCanvas(string $startCanvas): self
    {
        $this->startCanvas = $startCanvas;

        return $this;
    }

    /**
     * @return array
     */
    public function getCanvases(): array
    {
        return $this->canvases;
    }

    /**
     * @param array $canvases
     *
     * @return Sequence
     */
    public function setCanvases(array $canvases): self
    {
        $this->canvases = $canvases;

        return $this;
    }

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
     * @return Sequence
     */
    public function setContext(string $context): self
    {
        $this->context = $context;

        return $this;
    }
}
