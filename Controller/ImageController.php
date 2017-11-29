<?php

declare(strict_types=1);

namespace Subugoe\IIIFBundle\Controller;

use FOS\RestBundle\View\View;
use Subugoe\IIIFBundle\Model\Image\Image;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Subugoe\IIIFBundle\Service\FileService;
use Subugoe\IIIFBundle\Service\ImageService;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\FOSRestController as Controller;

class ImageController extends Controller
{
    /**
     * @var ImageService
     */
    private $imageService;

    /**
     * @var FileService
     */
    private $fileService;

    public function __construct(ImageService $imageService, FileService $fileService)
    {
        $this->imageService = $imageService;
        $this->fileService = $fileService;
    }

    /**
     * {scheme}://{server}{/prefix}/{identifier}/{region}/{size}/{rotation}/{quality}.{format}.
     *
     * @see http://iiif.io/api/image/2.1/#uri-syntax
     * @ApiDoc(
     *  resource=true,
     *  description="IIIF presentation API sequence resource",
     *  requirements={
     *      {"name"="identifier", "dataType"="string", "required"=true, "description"="Image identifier"},
     *      {"name"="region", "dataType"="string", "required"=true,
     *          "description"="The region parameter defines the rectangular portion of the full image to be returned. Region can be specified by pixel coordinates (x,y,w,h), percentage (pct:x,y,w,h) or by the value “full”, which specifies that the entire image should be returned. See http://iiif.io/api/image/2.1/#region for more details."
     *      },
     *      {"name"="size", "dataType"="string", "required"=true,
     *         "description"="The size parameter determines the dimensions to which the extracted region is to be scaled. Possible values: 'full', 'max', 'w,', ',h', 'pct:n', 'w,h', '!w,h'. See http://iiif.io/api/image/2.1/#size for more details."
     *      },
     *      {"name"="rotation", "dataType"="string", "required"=true,
     *         "description"="The rotation parameter specifies mirroring and rotation. A leading exclamation mark (“!”) indicates that the image should be mirrored by reflection on the vertical axis before any rotation is applied. The numerical value represents the number of degrees of clockwise rotation, and may be any floating point number from 0 to 360. See http://iiif.io/api/image/2.1/#rotation for more details."
     *      },
     *      {"name"="quality", "dataType"="string", "required"=true,
     *         "description"="The quality parameter determines whether the image is delivered in color, grayscale or black and white. See http://iiif.io/api/image/2.1/#quality for more details."
     *      },
     *      {"name"="format", "dataType"="string", "required"=true,
     *         "description"="The format of the returned image is expressed as an extension at the end of the URI. See http://iiif.io/api/image/2.1/#format for more details."
     *      }
     *  }
     * )
     *
     * @var string
     * @var string $region
     * @var string $size
     * @var string $rotation
     * @var string $quality
     * @var string $format
     *
     * @return Response
     */
    public function indexAction(string $identifier, string $region, string $size, string $rotation, string $quality, string $format): Response
    {
        $cacheFilesystem = $this->fileService->getCacheFilesystem();

        $imageEntity = new Image();
        $imageEntity
            ->setIdentifier($identifier)
            ->setRegion($region)
            ->setSize($size)
            ->setRotation($rotation)
            ->setQuality($quality)
            ->setFormat($format);

        $cachedFile = $this->imageService->getCachedFileIdentifier($imageEntity);

        $response = (new Response())
            ->setPublic()
            ->setSharedMaxAge(86400);

        if ($cacheFilesystem->has($cachedFile)) {
            $response->headers->add(['content-type' => $cacheFilesystem->getMimetype($cachedFile)]);
            $response->setContent($cacheFilesystem->read($cachedFile));
            $response->setEtag(md5($response->getContent()));

            return $response;
        }

        $image = $this->imageService->process($imageEntity);

        $cacheFilesystem->write($cachedFile, $image);

        $response->setContent($image);
        $response->setEtag(md5($response->getContent()));
        $response->headers->add(['content-type' => $cacheFilesystem->getMimetype($cachedFile)]);

        return $response;
    }

    /**
     * @Route("/image/{identifier}/info.json", name="_iiifjson", methods={"GET"})
     */
    public function infoJsonAction(string $identifier): View
    {
        $cacheIdentifier = sprintf('infojson-%s', hash('sha256', $identifier));
        $cachedInfoJson = $this->get('cache.app')->getItem($cacheIdentifier);
        if (!$cachedInfoJson->isHit()) {
            $imageEntity = new Image();
            $imageEntity->setIdentifier($identifier);

            if (!$this->imageService->getOriginalFileContents($imageEntity)) {
                $image = null;
            } else {
                $image = $this->imageService->getImageJsonInformation($identifier, $this->imageService->getOriginalFileContents($imageEntity));
                $cachedInfoJson->set($image);
                $this->get('cache.app')->save($cachedInfoJson);
            }
        } else {
            $image = $cachedInfoJson->get();
        }

        if (null === $image) {
            $error = ['error' => 'Image not found.'];
            $view = $this->view($error, Response::HTTP_NOT_FOUND);
        } else {
            $view = $this->view($image, Response::HTTP_OK);
            $view->setHeader('Cache-Control', 's-maxage=86400');
            $view->setHeader('ETag', md5(serialize($image)));
        }

        return $view;
    }
}
