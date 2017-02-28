<?php

declare(strict_types=1);

namespace Subugoe\IIIFBundle\Controller;

use FOS\RestBundle\View\View;
use Subugoe\IIIFBundle\Model\Image\Image;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
     * @return BinaryFileResponse
     * @Route("/image/{identifier}/{region}/{size}/{rotation}/{quality}.{format}", name="_image", methods={"GET"})
     */
    public function indexAction(string $identifier, string $region, string $size, string $rotation, string $quality, string $format): BinaryFileResponse
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
            '%s/%s.%s',
            [
                $this->getParameter('image_cache'),
                $hash,
                $imageEntity->getFormat(),
            ]
        );

        $this->createCacheDirectory($cachedFile);

        $fs = new Filesystem();

        if ($fs->exists($cachedFile)) {
            return new BinaryFileResponse($cachedFile);
        }

        $imagine = $this->get('liip_imagine');
        $imageService = $this->get('subugoe_iiif.image_service');

        try {
            $image = $imagine->load($this->getOriginalFileContents($imageEntity, $identifier));
        } catch (\Exception $e) {
            throw new NotFoundHttpException(sprintf('Image with identifier %s not found', $imageEntity->getIdentifier()));
        }

        $imageService->getRegion($imageEntity->getRegion(), $image);
        $imageService->getSize($imageEntity->getSize(), $image);
        $imageService->getRotation($imageEntity->getRotation(), $image);
        $imageService->getQuality($imageEntity->getQuality(), $image);

        $image
            ->strip()
            ->save($cachedFile,
                ['format' => $imageEntity->getFormat()]
            );

        return new BinaryFileResponse($cachedFile);
    }

    /**
     * @param string $identifier
     *
     * @return string
     */
    protected function getImageFormat($identifier)
    {
        $client = $this->get('solarium.client');
        $selectDocument = $client->createSelect()
            ->setQuery(sprintf('work:%s', $identifier));

        $document = $client->select($selectDocument)->getDocuments()[0];

        if (isset($document['image_format'])) {
            $format = $document['image_format'];
        } else {
            $format = $this->getParameter('default_backend_image_format');
        }

        return $format;
    }

    /**
     * @Route("/image/{identifier}/info.json", name="_iiifjson", methods={"GET"})
     */
    public function infoJsonAction(string $identifier): View
    {
        $imageService = $this->get('subugoe_iiif.image_service');
        $imageEntity = new Image();
        $imageEntity->setIdentifier($identifier);
        $image = $imageService->getImageJsonInformation($identifier, $this->getOriginalFileContents($imageEntity, $identifier));

        return $this->view($image, Response::HTTP_OK);
    }

    /**
     * @param Image $image
     *
     * @return \Psr\Http\Message\StreamInterface|string
     */
    protected function getOriginalFileContents(Image $image, string $originalIdentifier)
    {
        $client = $this->get('guzzle.client.tiff');
        $fs = new Filesystem();

        $originalImageCacheFile = $this->getParameter('kernel.cache_dir').'/originals/'.$originalIdentifier.'.tif';

        $this->createCacheDirectory($originalImageCacheFile);

        if ($fs->exists($originalImageCacheFile)) {
            $originalImage = file_get_contents($originalImageCacheFile);
        } else {
            $originalImage = $client->get($image->getIdentifier().'.tif', ['sink' => $originalImageCacheFile])->getBody();
        }

        return $originalImage;
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

    /**
     * @param string $file
     */
    protected function createCacheDirectory(string $file)
    {
        $fs = new Filesystem();

        if (!$fs->exists(dirname($file))) {
            $fs->mkdir(dirname($file));
        }
    }

    /**
     * @param ConstraintViolationListInterface $errors
     */
    protected function processErrors(ConstraintViolationListInterface $errors)
    {
        $errorCounter = count($errors);

        if ($errorCounter > 0) {
            $errorMessages = [];

            for ($i = 0; $i < $errorCounter; ++$i) {
                $errorMessages[] = $errors->get($i)->getMessage();
            }

            throw new BadRequestHttpException(implode('. ', $errorMessages));
        }
    }
}
