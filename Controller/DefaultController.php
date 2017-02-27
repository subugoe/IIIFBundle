<?php

namespace Subugoe\IIIFBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\FOSRestController as Controller;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Get("/api/{id}/manifest")
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
        $documentService = $this->get('document_service');
        $document = $documentService->getDocumentDataById($id, $this->getParameter('details_fields'))->getFields();

        return $this->view($documentService->getManifest($document), Response::HTTP_OK);
    }

    /**
     * @Get("/api/{id}/canvas/{canvas}", name="_canvas")
     *
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
        return $this->view($this->get('document_service')->getCanvas($id, $canvas), Response::HTTP_OK);
    }

    /**
     * @Get("/api/{id}/annotation/{name}", name="_imagepresentation")
     *
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
        return $this->view($this->get('document_service')->getImage($id, $name), Response::HTTP_OK);
    }

    /**
     * @Get("/api/{id}/sequence/{name}", name="_sequence", defaults={"name"="normal"})
     *
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
        return $this->view($this->get('document_service')->getSequence($id, $name), Response::HTTP_OK);
    }

    /**
     * @Get("/api/{id}/range/{range}", name="_range")
     */
    public function rangeAction(string $id, string $range)
    {
        return new Response('Range');
    }
}
