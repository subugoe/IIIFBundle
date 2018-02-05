<?php

namespace Subugoe\IIIFBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController as Controller;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Subugoe\IIIFBundle\Service\PresentationService;
use Subugoe\IIIFBundle\Translator\TranslatorInterface;
use Symfony\Component\HttpFoundation\Response;

class PresentationController extends Controller
{
    /**
     * @var PresentationService
     */
    private $presentationService;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * PresentationController constructor.
     *
     * @param PresentationService $presentationService
     */
    public function __construct(PresentationService $presentationService, TranslatorInterface $translator)
    {
        $this->presentationService = $presentationService;
        $this->translator = $translator;
    }

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="IIIF presentation API manifest resource",
     *  requirements={
     *      {"name"="id", "dataType"="string", "required"=true, "description"="work identifier"}
     *  }
     * )
     */
    public function manifestAction(string $id)
    {
        $document = $this->translator->getDocumentById($id);

        return $this->view($this->presentationService->getManifest($document), Response::HTTP_OK);
    }

    /**
     * @see http://iiif.io/api/presentation/2.1/#canvas
     * @ApiDoc(
     *  resource=true,
     *  description="IIIF presentation API canvas resource",
     *  requirements={
     *      {"name"="id", "dataType"="string", "required"=true, "description"="work identifier"},
     *      {"name"="canvas", "dataType"="string", "required"=true, "description"="canvas identifier"}
     *  }
     * )
     */
    public function canvasAction(string $id, string $canvas): View
    {
        $document = $this->translator->getDocumentById($id);

        return $this->view($this->presentationService->getCanvas($document, $canvas), Response::HTTP_OK);
    }

    /**
     * @see http://iiif.io/api/presentation/2.1/#image-resources
     * @ApiDoc(
     *  resource=true,
     *  description="IIIF presentation API image resource",
     *  requirements={
     *      {"name"="id", "dataType"="string", "required"=true, "description"="work identifier"},
     *      {"name"="name", "dataType"="string", "required"=true, "description"="image identifier"}
     *  }
     * )
     */
    public function imageAction(string $id, string $name): View
    {
        $document = $this->translator->getDocumentById($id);

        return $this->view($this->presentationService->getImage($document, $name), Response::HTTP_OK);
    }

    /**
     * @see http://iiif.io/api/presentation/2.1/#sequence
     * @ApiDoc(
     *  resource=true,
     *  description="IIIF presentation API sequence resource",
     *  requirements={
     *      {"name"="id", "dataType"="string", "required"=true, "description"="work identifier"},
     *      {"name"="name", "dataType"="string", "required"=true, "description"="sequence identifier, default is 'normal'"}
     *  }
     * )
     *
     * @return View
     */
    public function sequenceAction(string $id, string $name): View
    {
        $document = $this->translator->getDocumentById($id);

        return $this->view($this->presentationService->getSequence($document, $name), Response::HTTP_OK);
    }

    /**
     * @see http://iiif.io/api/presentation/2.1/#annotation-list
     * @ApiDoc(
     *  resource=true,
     *  description="IIIF presentation API annotation list resource",
     *  requirements={
     *      {"name"="id", "dataType"="string", "required"=true, "description"="work identifier"},
     *      {"name"="name", "dataType"="string", "required"=true, "description"="annotation list identifier'"}
     *  }
     * )
     *
     * @return View
     */
    public function annotationListAction(string $id, string $name): View
    {
        $document = $this->translator->getDocumentById($id);

        return $this->view($this->presentationService->getAnnotationList($document, $name), Response::HTTP_OK);
    }

    /**
     * @see http://iiif.io/api/presentation/2.1/#range
     * @ApiDoc(
     *  resource=true,
     *  description="IIIF presentation API range resource",
     *  requirements={
     *      {"name"="id", "dataType"="string", "required"=true, "description"="work identifier"},
     *      {"name"="name", "dataType"="string", "required"=true, "description"="range identifier'"}
     *  }
     * )
     */
    public function rangeAction(string $id, string $range)
    {
        $document = $this->translator->getDocumentById($id);

        return $this->view($this->presentationService->getRange($document, $range), Response::HTTP_OK);
    }

    /**
     * @see http://iiif.io/api/presentation/2.1/#collection
     * @ApiDoc(
     *  resource=true,
     *  description="IIIF presentation API collection resource",
     *  requirements={
     *      {"name"="id", "dataType"="string", "required"=true, "description"="collection identifier"}
     *  }
     * )
     *
     * @return View
     */
    public function collectionAction(string $id)
    {
        $collection = $this->translator->getCollectionById($id);

        return $this->view($this->presentationService->getCollection($collection), Response::HTTP_OK);
    }

    /**
     * @see http://iiif.io/api/presentation/2.1/#collection
     * @ApiDoc(
     *  resource=true,
     *  description="IIIF presentation API showing all collections",
     * )
     *
     * @return View
     */
    public function collectionsAction()
    {
        $collections = $this->translator->getCollections();

        return $this->view($this->presentationService->getCollections($collections));
    }
}
