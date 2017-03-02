<?php

declare(strict_types=1);

namespace Subugoe\IIIFBundle\Controller;

use FOS\RestBundle\View\View;
use Subugoe\IIIFBundle\Model\Image\Image;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\FOSRestController as Controller;

class ImageController extends Controller
{
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
        $imageEntity = new Image();
        $imageEntity
            ->setIdentifier($identifier)
            ->setRegion($region)
            ->setSize($size)
            ->setRotation($rotation)
            ->setQuality($quality)
            ->setFormat($format);

        $hash = sha1(serialize(func_get_args()));
        $cachedFile = vsprintf(
            '%s.%s',
            [
                $hash,
                $imageEntity->getFormat(),
            ]
        );

        $imageParameters = $this->getParameter('image');

        $cacheAdapterConfiguration = $imageParameters['adapters']['cache']['configuration'];
        $cacheAdapterClass = $imageParameters['adapters']['cache']['class'];
        $cacheFilesystemAdapter = new $cacheAdapterClass($cacheAdapterConfiguration);
        $cacheFilesystem = new \League\Flysystem\Filesystem($cacheFilesystemAdapter);

        $response = new Response();
        if ($cacheFilesystem->has($cachedFile)) {
            $response->headers->add(['content-type' => $cacheFilesystem->getMimetype($cachedFile)]);
            $response->setContent($cacheFilesystem->read($cachedFile));

            return $response;
        }

        $imagine = $this->get('liip_imagine');
        $imageService = $this->get('subugoe_iiif.image_service');

        $image = $imagine->load($imageService->getOriginalFileContents($imageEntity, $identifier));

        $imageService->getRegion($imageEntity->getRegion(), $image);
        $imageService->getSize($imageEntity->getSize(), $image);
        $imageService->getRotation($imageEntity->getRotation(), $image);
        $imageService->getQuality($imageEntity->getQuality(), $image);

        $tempImage = $image
            ->strip()
            ->get($imageEntity->getFormat());

        $cacheFilesystem->write($cachedFile, $tempImage);

        $response->setContent($tempImage);
        $response->headers->add(['content-type' => $cacheFilesystem->getMimetype($cachedFile)]);

        return $response;
    }

    /**
     * @Route("/image/{identifier}/info.json", name="_iiifjson", methods={"GET"})
     */
    public function infoJsonAction(string $identifier): View
    {
        $imageService = $this->get('subugoe_iiif.image_service');
        $imageEntity = new Image();
        $imageEntity->setIdentifier($identifier);
        $image = $imageService->getImageJsonInformation($identifier, $imageService->getOriginalFileContents($imageEntity, $identifier));

        return $this->view($image, Response::HTTP_OK);
    }

    /**
     * @Route("/image/view/{identifier}", name="_iiifview", methods={"GET"})
     */
    public function viewAction(string $identifier): Response
    {
        return $this->render('images/view.html.twig', [
            'identifier' => $identifier,
        ]);
    }
}
