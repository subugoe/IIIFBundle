<?php

declare(strict_types=1);

namespace Subugoe\IIIFBundle\Service;

use Imagine\Image\Box;
use Imagine\Image\BoxInterface;
use Imagine\Image\ImageInterface;
use Imagine\Image\Point;
use Imagine\Image\Profile;
use Imagine\Imagick\Imagine;
use League\Flysystem\FilesystemException;
use Subugoe\IIIFBundle\Translator\TranslatorInterface;
use Subugoe\IIIFModel\Model\Document;
use Subugoe\IIIFModel\Model\Image\Dimension;
use Subugoe\IIIFModel\Model\Image\Image;
use Subugoe\IIIFModel\Model\Image\ImageInformation;
use Subugoe\IIIFModel\Model\Image\Tile;
use Subugoe\IIIFModel\Model\PhysicalStructure;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Image manipulation service.
 */
class ImageService implements ImageServiceInterface
{
    private Imagine $imagine;

    private Router $router;

    private TranslatorInterface $translator;

    private FileService $fileService;

    private array $imageConfiguration;

    /**
     * ImageService constructor.
     */
    public function __construct(
        Imagine $imagine,
        Router $router,
        TranslatorInterface $translator,
        FileService $fileService
    ) {
        $this->imagine = $imagine;
        $this->router = $router;
        $this->translator = $translator;
        $this->fileService = $fileService;
    }

    public function setImageConfiguration(array $imageConfiguration): void
    {
        $this->imageConfiguration = $imageConfiguration;
    }

    public function getImageConfiguration(): array
    {
        return $this->imageConfiguration;
    }

    public function process(Image $imageEntity): string
    {
        $image = $this->imagine->load($this->getOriginalFileContents($imageEntity));

        // strip the profile, as Firefox uses this and may display images with strange colors
        $profile = new Profile('', '');
        $image = $image->profile($profile);

        $this->getRegion($imageEntity->getRegion(), $image);
        $this->getSize($imageEntity->getSize(), $image);
        $this->getRotation($imageEntity->getRotation(), $image);
        $this->getQuality($imageEntity->getQuality(), $image);

        return $image->get($imageEntity->getFormat());
    }

    public function getImageJsonInformation(string $identifier, $originalImage): ImageInformation
    {
        $imageEntity = new Image();
        $imageEntity->setIdentifier($identifier);

        try {
            $image = $this->imagine->load($originalImage);
        } catch (\Exception $e) {
            throw new NotFoundHttpException(sprintf('Image with identifier %s not found', $imageEntity->getIdentifier()));
        }

        $ppi = $image->getImagick()->getImageResolution();
        $image = $image->strip();
        $originalSize = $image->getSize();
        $sizeList = $this->imageConfiguration['zoom_levels'];
        $sizes = $this->getImageSizes($originalSize);

        $tiles = $this->getTileInformation($sizeList);

        if (array_key_exists('host', $this->imageConfiguration['http'])) {
            $context = $this->router->getContext();
            $url = sprintf('%s://%s', $this->imageConfiguration['http']['scheme'], $this->imageConfiguration['http']['host']);
            $urlParts = parse_url($url);

            if (isset($urlParts['port'])) {
                $context->setHttpPort($urlParts['port']);
            }

            $context
                ->setHost($urlParts['host'])
                ->setScheme($urlParts['scheme']);
        }

        $imageInformation = new ImageInformation();
        $imageInformation
            ->setId($this->router->generate('subugoe_iiif_image_base', ['identifier' => $identifier],
                UrlGeneratorInterface::ABSOLUTE_URL))
            ->setPpi($ppi)
            ->setWidth($originalSize->getWidth())
            ->setHeight($originalSize->getHeight())
            ->setSizes($sizes)
            ->setTiles($tiles);

        return $imageInformation;
    }

    /**
     * Stores the original image in a cache file.
     *
     * @return false|string
     *
     * @throws FilesystemException
     */
    public function getOriginalFileContents(Image $image)
    {
        $document = $this->translator->getDocumentByImageId($image->getIdentifier());
        $filename = $this->getFilename($document, $image);

        $sourceFilesystem = $this->fileService->getSourceFilesystem();
        $cacheFilesystem = $this->fileService->getCacheFilesystem();
        $originalImageCacheFile = sprintf('/orig/%s.%s', $image->getIdentifier(), $document->getImageFormat());

        if ($this->imageConfiguration['originals_caching'] && !$cacheFilesystem->fileExists($originalImageCacheFile)) {
            $sourceImage = $sourceFilesystem->read($filename);
            $cacheFilesystem->write($originalImageCacheFile, $sourceImage);
        }

        if ($cacheFilesystem->fileExists($originalImageCacheFile)) {
            return $cacheFilesystem->read($originalImageCacheFile);
        }

        if (!$sourceFilesystem->fileExists($filename)) {
            return false;
        }

        return $sourceFilesystem->read($filename);
    }

    public function getCachedFileIdentifier(Image $image): string
    {
        return vsprintf(
            '%s/%s.%s',
            [
                $image->getIdentifier(),
                $this->getImageHash($image),
                $image->getFormat(),
            ]
        );
    }

    private function getImageHash(Image $image): string
    {
        return hash('sha256', serialize($image));
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
     * @param ImageInterface $image The image object
     *
     * @throws BadRequestHttpException if a region parameter missing or parameter out of image bound
     *
     * @return ImageInterface
     */
    private function getRegion(string $region, ImageInterface $image): void
    {
        $region = trim($region);

        if ('full' === $region) {
            return;
        }

        $sourceImageWidth = $image->getSize()->getWidth();
        $sourceImageHeight = $image->getSize()->getHeight();

        if ('square' === $region) {
            $regionSort = 'squareBased';
        } elseif (false !== strpos($region, 'pct')) {
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
     * @param ImageInterface $image The image object
     *
     * @throws BadRequestHttpException if wrong size syntax given
     *
     * @return ImageInterface
     */
    private function getSize(string $size, ImageInterface $image): void
    {
        if ('full' === $size || 'max' === $size) {
            return;
        }

        $rawSize = $size;
        if (false !== strpos($size, '!')) {
            $size = str_replace('!', '', $size);
        }
        $regionWidth = $image->getSize()->getWidth();
        $regionHeight = $image->getSize()->getHeight();
        if (false === strpos($size, 'pct')) {
            $requestedSize = explode(',', $size);
            if (2 != count($requestedSize)) {
                throw new BadRequestHttpException(sprintf('Bad Request: Size syntax %s is not valid.', $size));
            }
            $width = $requestedSize[0];
            $height = $requestedSize[1];
            if (false !== strpos($rawSize, '!')) {
                $w = (($regionWidth / $regionHeight) * $height);
                $h = (($regionHeight / $regionWidth) * $width);
            } else {
                $w = empty($width) ? ($regionWidth / $regionHeight) * $height : $width;
                $h = empty($height) ? ($regionHeight / $regionWidth) * $width : $height;
            }
            $image->resize(new Box($w, $h));
        } elseif (false !== strpos($size, 'pct')) {
            $requestedPercentage = explode(':', $size)[1];
            if (is_numeric($requestedPercentage)) {
                $w = (($regionWidth * $requestedPercentage) / 100);
                $h = (($regionHeight * $requestedPercentage) / 100);
                $image->resize(new Box($w, $h));
            } else {
                throw new BadRequestHttpException(sprintf('Bad Request: Size syntax %s is not valid.', $size));
            }
        }
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
     * @param ImageInterface $image The image object
     *
     * @throws BadRequestHttpException if wrong rotation parameters provided
     *
     * @return ImageInterface
     */
    private function getRotation(string $rotation, ImageInterface $image): void
    {
        if (0 === (int) $rotation) {
            return;
        }

        if (!empty($rotation)) {
            $rotationDegree = str_replace('!', '', $rotation);
            if ((int) $rotationDegree <= 360) {
                if (false !== strpos($rotation, '!')) {
                    $image->flipVertically();
                }
                $image->rotate(str_replace('!', '', $rotation));
            } else {
                throw new BadRequestHttpException(sprintf('Bad Request: Rotation argument %s is not between 0 and 360.', $rotationDegree));
            }
        }
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
     * @param ImageInterface $image The image object
     *
     * @throws BadRequestHttpException if wrong quality parameters provided
     *
     * @return ImageInterface
     */
    private function getQuality(string $quality, ImageInterface $image): void
    {
        if ('default' === $quality || 'color' === $quality) {
            return;
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
    }

    private function getImageSizes(BoxInterface $originalSize): array
    {
        $sizes = [];
        $sizeList = $this->imageConfiguration['zoom_levels'];

        foreach ($sizeList as $size) {
            $dimension = new Dimension();
            $dimension
                ->setHeight($originalSize->getHeight() / $size)
                ->setWidth($originalSize->getWidth() / $size);

            $sizes[] = $dimension;
        }

        return array_reverse($sizes);
    }

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

    private function getFilename(Document $document, Image $image): string
    {
        /** @var PhysicalStructure $physicalStructure */
        foreach ($document->getPhysicalStructures() as $physicalStructure) {
            if ($image->getIdentifier() === $physicalStructure->getIdentifier()) {
                return $physicalStructure->getFilename();
            }
        }

        return $image->getIdentifier().$image->getFormat();
    }
}
