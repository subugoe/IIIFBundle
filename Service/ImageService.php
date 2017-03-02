<?php

declare(strict_types=1);

namespace Subugoe\IIIFBundle\Service;

use Imagine\Image\Box;
use Imagine\Image\BoxInterface;
use Imagine\Image\ImagineInterface;
use Imagine\Image\Point;
use Imagine\Imagick\Image;
use Subugoe\IIIFBundle\Model\Image\Dimension;
use Subugoe\IIIFBundle\Model\Image\ImageInformation;
use Subugoe\IIIFBundle\Model\Image\Tile;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Image manipulation service.
 */
class ImageService
{
    /**
     * @var ImagineInterface
     */
    private $imagine;

    /**
     * @var Router
     */
    private $router;

    /**
     * ImageService constructor.
     *
     * @param ImagineInterface $imagine
     * @param Router           $router
     * @param array            $imageConfiguration
     */
    public function __construct(ImagineInterface $imagine, Router $router, array $imageConfiguration)
    {
        $this->imagine = $imagine;
        $this->router = $router;
        $this->imageConfiguration = $imageConfiguration;
    }

    /*
     * Apply the requested image region as per IIIF-Image API.
     * Region parameters may be:
     *      - full
     *      - x,y,w,h
     *      - pct:x,y,w,h
     *
     * @see http://iiif.io/api/image/2.0/#region
     *
     * @param string $region  The requested image region
     * @param Image $image The image object
     *
     * @throws BadRequestHttpException if a region parameter missing or parameter out of image bound
     */
    public function getRegion(string $region, Image $image): Image
    {
        $region = trim($region);

        if ($region === 'full') {
            return $image;
        }

        $sourceImageWidth = $image->getSize()->getWidth();
        $sourceImageHeight = $image->getSize()->getHeight();

        if ($region === 'square') {
            $regionSort = 'squareBased';
        } elseif (strstr($region, 'pct')) {
            $regionSort = 'percentageBased';
        } else {
            $regionSort = 'pixelBased';
        }

        switch ($regionSort) {
            case 'squareBased':
                $calculateShorterDimension = $sourceImageWidth < $sourceImageHeight ? $sourceImageWidth : $sourceImageHeight;
                $calculateLongerDimension = $sourceImageWidth < $sourceImageHeight ? $sourceImageHeight : $sourceImageWidth;
                $imageLeftRightMargin = (($calculateLongerDimension - $calculateShorterDimension) / 2);
                $x = 0;
                $y = $imageLeftRightMargin;
                $w = $calculateShorterDimension;
                $h = $calculateShorterDimension;
                break;
            case 'pixelBased':
                $imageCoordinates = explode(',', $region);
                if (count($imageCoordinates) < 4) {
                    throw new BadRequestHttpException('Bad Request: Exactly (4) coordinates must be supplied.');
                }
                $x = $imageCoordinates[0];
                $y = $imageCoordinates[1];
                $w = $imageCoordinates[2];
                $h = $imageCoordinates[3];
                break;
            case 'percentageBased':
                $imageCoordinates = explode(',', explode(':', $region)[1]);
                if (count($imageCoordinates) < 4) {
                    throw new BadRequestHttpException('Bad Request: Exactly (4) coordinates must be supplied.');
                }
                if ((isset($imageCoordinates[0]) && $imageCoordinates[0] >= 100) ||
                        (isset($imageCoordinates[1]) && $imageCoordinates[1] >= 100)) {
                    throw new BadRequestHttpException('Bad Request: Crop coordinates are out of bound.');
                }
                $x = ceil(($imageCoordinates[0] / 100) * $sourceImageWidth);
                $y = ceil(($imageCoordinates[1] / 100) * $sourceImageHeight);
                $w = ceil(($imageCoordinates[2] / 100) * $sourceImageWidth);
                $h = ceil(($imageCoordinates[3] / 100) * $sourceImageHeight);
                break;
            default:
                $x = 0;
                $y = 0;
                $w = $sourceImageWidth;
                $h = $sourceImageHeight;
        }

        $image->crop(new Point($x, $y), new Box($w, $h));

        return $image;
    }

    /*
     * Apply the requested image size as per IIIF-Image API
     * Size parameters may be:
     *      - full
     *      - w,
     *      - ,h
     *      - pct:n
     *      - w,h
     *      - !w,h
     *
     * @see http://iiif.io/api/image/2.0/#size
     *
     * @param string $size The requested image size
     * @param Image $image The image object
     *
     * @throws BadRequestHttpException if wrong size syntax given
     */
    public function getSize(string $size, Image $image): Image
    {
        if ($size === 'full' || $size === 'max') {
            return $image;
        }

        $rawSize = $size;
        if (strstr($size, '!')) {
            $size = str_replace('!', '', $size);
        }
        $regionWidth = $image->getSize()->getWidth();
        $regionHeight = $image->getSize()->getHeight();
        if (!strstr($size, 'pct')) {
            $requestedSize = explode(',', $size);
            if (count($requestedSize) != 2) {
                throw new BadRequestHttpException(sprintf('Bad Request: Size syntax %s is not valid.', $size));
            }
            $width = $requestedSize[0];
            $height = $requestedSize[1];
            if (strstr($rawSize, '!')) {
                $w = (($regionWidth / $regionHeight) * $height);
                $h = (($regionHeight / $regionWidth) * $width);
            } else {
                if (!empty($width)) {
                    $w = $width;
                } else {
                    $w = (($regionWidth / $regionHeight) * $height);
                }
                if (!empty($height)) {
                    $h = $height;
                } else {
                    $h = (($regionHeight / $regionWidth) * $width);
                }
            }
            $image->resize(new Box($w, $h));
        } elseif (strstr($size, 'pct')) {
            $requestedPercentage = explode(':', $size)[1];
            if (is_numeric($requestedPercentage)) {
                $w = (($regionWidth * $requestedPercentage) / 100);
                $h = (($regionHeight * $requestedPercentage) / 100);
                $image->resize(new Box($w, $h));
            } else {
                throw new BadRequestHttpException(sprintf('Bad Request: Size syntax %s is not valid.', $size));
            }
        }

        return $image;
    }

    /*
     * Apply the requested image rotation as per IIIF-Image API
     * Rotation parameters may be:
     *      - n
     *      - !n
     *
     * @see http://iiif.io/api/image/2.0/#rotation
     *
     * @param string $rotation The requested image rotation
     * @param Image $image The image object
     *
     * @throws BadRequestHttpException if wrong rotation parameters provided
     */
    public function getRotation(string $rotation, Image $image): Image
    {
        if (isset($rotation) && !empty($rotation)) {
            $rotationDegree = str_replace('!', '', $rotation);
            if (intval($rotationDegree) <= 360) {
                if (strstr($rotation, '!')) {
                    $image->flipVertically();
                }
                $image->rotate(str_replace('!', '', $rotation));
            } else {
                throw new BadRequestHttpException(sprintf('Bad Request: Rotation argument %s is not between 0 and 360.', $rotationDegree));
            }
        }

        return $image;
    }

    /*
     * Apply the requested image quality as per IIIF-Image API
     *
     * Quality parameters may be:
     *      - color
     *      - gray
     *      - bitonal
     *      - default
     *
     * @see http://iiif.io/api/image/2.0/#quality
     *
     * @param string $quality The requested image quality
     * @param Image $image The image object
     *
     * @throws BadRequestHttpException if wrong quality parameters provided
     */
    public function getQuality(string $quality, Image $image): Image
    {
        if ($quality === 'default' || $quality === 'color') {
            return $image;
        }

        switch ($quality) {
            case 'gray':
                $image->effects()->grayscale();
                break;
            case 'bitonal':
                $max = $image->getImagick()->getQuantumRange();
                $max = $max['quantumRangeLong'];
                $imageClearnessFactor = 0.20;
                $image->getImagick()->thresholdImage($max * $imageClearnessFactor);
                break;
            default:
                throw new BadRequestHttpException(sprintf('Bad Request: %s is not a supported quality.', $quality));
        }

        return $image;
    }

    /**
     * @param string $identifier
     *
     * @return ImageInformation
     */
    public function getImageJsonInformation(string $identifier, $originalImage)
    {
        $imageEntity = new \Subugoe\IIIFBundle\Model\Image\Image();
        $imageEntity->setIdentifier($identifier);

        try {
            $image = $this->imagine->load($originalImage);
        } catch (\Exception $e) {
            throw new NotFoundHttpException(sprintf('Image with identifier %s not found', $imageEntity->getIdentifier()));
        }

        $ppi = $image->getImagick()->getImageResolution();
        $image->strip();
        $originalSize = $image->getSize();
        $sizeList = [1, 2, 4, 8, 16];
        $sizes = $this->getImageSizes($originalSize, $sizeList);

        $tiles = $this->getTileInformation($sizeList);

        $imageInformation = new ImageInformation();
        $imageInformation
           ->setId($this->router->generate('subugoe_iiif_image_json', ['identifier' => $identifier]))
           ->setPpi($ppi)
           ->setWidth($originalSize->getWidth())
           ->setHeight($originalSize->getHeight())
           ->setSizes($sizes)
           ->setTiles($tiles);

        return $imageInformation;
    }

    /**
     * @param BoxInterface $originalSize
     *
     * @return array
     */
    private function getImageSizes(BoxInterface $originalSize, array $sizeList): array
    {
        $sizes = [];

        foreach ($sizeList as $size) {
            $dimension = new Dimension();
            $dimension
                ->setHeight($originalSize->getHeight() / $size)
                ->setWidth($originalSize->getWidth() / $size);

            $sizes[] = $dimension;
        }

        return array_reverse($sizes);
    }

    /**
     * @param array $sizeList
     *
     * @return array
     */
    private function getTileInformation(array $sizeList): array
    {
        $tiles = [];
        $tile = new Tile();
        $tile
            ->setWidth($this->imageConfiguration['tile_width'])
            ->setHeight($this->imageConfiguration['tile_height'])
            ->setScaleFactors($sizeList);

        $tiles[] = $tile;

        return $tiles;
    }

    /**
     * Stores the original image in a cache file.
     *
     * @param \Subugoe\IIIFBundle\Model\Image\Image $image
     *
     * @return \Psr\Http\Message\StreamInterface|string
     */
    public function getOriginalFileContents(\Subugoe\IIIFBundle\Model\Image\Image $image, string $originalIdentifier)
    {
        $sourceAdapterConfiguration = $this->imageConfiguration['adapters']['source']['configuration'];
        $sourceAdapterClass = $this->imageConfiguration['adapters']['source']['class'];
        $sourceAdapter = new $sourceAdapterClass($sourceAdapterConfiguration);
        $sourceFilesystem = new \League\Flysystem\Filesystem($sourceAdapter);

        $cacheAdapterConfiguration = $this->imageConfiguration['adapters']['cache']['configuration'];
        $cacheAdapterClass = $this->imageConfiguration['adapters']['cache']['class'];
        $cacheFilesystemAdapter = new $cacheAdapterClass($cacheAdapterConfiguration);
        $cacheFilesystem = new \League\Flysystem\Filesystem($cacheFilesystemAdapter);

        $bild = $sourceFilesystem->read($image->getIdentifier().'.tif');

        $originalImageCacheFile = sprintf('/originals/%s.tif', $originalIdentifier);

        if (!$cacheFilesystem->has($originalImageCacheFile)) {
            $cacheFilesystem->write($originalImageCacheFile, $bild);
        }

        if ($cacheFilesystem->has($originalImageCacheFile)) {
            $originalImage = $cacheFilesystem->read($originalImageCacheFile);
        } else {
            $originalImage = $bild;
        }

        return $originalImage;
    }
}
