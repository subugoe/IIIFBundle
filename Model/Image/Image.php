<?php

declare(strict_types=1);

namespace Subugoe\IIIFBundle\Model\Image;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * IIIF image.
 */
class Image
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/^[(\w+:\w+)|(\w)]/", message="Invalid identifier format.")
     */
    protected $identifier;

    /**
     * @var string
     */
    protected $region = 'full';

    /**
     * @var string
     */
    protected $size;

    /**
     * @var string|int
     * @Assert\Regex(pattern="/^(!)?(-)?[0-9]{1,3}$/", message="Invalid rotation format")
     */
    protected $rotation = 0;

    /**
     * @var string
     */
    protected $quality = 'default';

    /**
     * @see http://iiif.io/api/image/2.0/#format
     *
     * @var string
     * @Assert\Choice(choices = {"jpg", "tif", "png", "gif", "jp2", "pdf", "webp"}, message="Please use a supported image format. See http://iiif.io/api/image/2.0/#format" )
     */
    protected $format = 'jpg';

    /**
     * @return string
     */
    public function getIdentifier()
    {
        if (preg_match('/\w:\w/', $this->identifier)) {
            $identifier = str_replace(':', '/', $this->identifier);
            $identifier = str_replace('gdz/', '', $identifier);
        } else {
            $identifier = $this->identifier.'/'.str_pad('1', 8, '0', STR_PAD_LEFT);
        }

        return $identifier;
    }

    /**
     * @param string $identifier
     *
     * @return Image
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param string $region
     *
     * @return Image
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param string $size
     *
     * @return Image
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return int|string
     */
    public function getRotation()
    {
        return $this->rotation;
    }

    /**
     * @param int|string $rotation
     *
     * @return Image
     */
    public function setRotation($rotation)
    {
        $this->rotation = $rotation;

        return $this;
    }

    /**
     * @return string
     */
    public function getQuality()
    {
        return $this->quality;
    }

    /**
     * @param string $quality
     *
     * @return Image
     */
    public function setQuality($quality)
    {
        $this->quality = $quality;

        return $this;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param string $format
     *
     * @return Image
     */
    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }
}
