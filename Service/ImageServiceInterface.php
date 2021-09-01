<?php

namespace Subugoe\IIIFBundle\Service;

use Psr\Http\Message\StreamInterface;
use Subugoe\IIIFModel\Model\Image\Image;
use Subugoe\IIIFModel\Model\Image\ImageInformation;

/**
 * Image manipulation service.
 */
interface ImageServiceInterface
{
    public function setImageConfiguration(array $imageConfiguration): void;

    public function getImageConfiguration(): array;

    public function process(Image $imageEntity): string;

    public function getImageJsonInformation(string $identifier, $originalImage): ImageInformation;

    /**
     * Stores the original image in a cache file.
     *
     * @return StreamInterface|string
     */
    public function getOriginalFileContents(Image $image);

    public function getCachedFileIdentifier(Image $image): string;
}
