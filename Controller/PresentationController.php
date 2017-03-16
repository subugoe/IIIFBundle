<?php

namespace Subugoe\IIIFBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\FOSRestController as Controller;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Response;

class PresentationController extends Controller
{
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
        $document = $this->get('subugoe_iiif.translator')->getDocumentById($id);

        return $this->view($this->get('subugoe_iiif.presentation_service')->getManifest($document), Response::HTTP_OK);
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
        $document = $this->get('subugoe_iiif.translator')->getDocumentById($id);

        return $this->view($this->get('subugoe_iiif.presentation_service')->getCanvas($document, $canvas), Response::HTTP_OK);
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
        $document = $this->get('subugoe_iiif.translator')->getDocumentById($id);

        return $this->view($this->get('subugoe_iiif.presentation_service')->getImage($document, $name), Response::HTTP_OK);
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
        $document = $this->get('subugoe_iiif.translator')->getDocumentById($id);

        return $this->view($this->get('subugoe_iiif.presentation_service')->getSequence($document, $name), Response::HTTP_OK);
    }

    /**
     * @Get("/api/{id}/range/{range}", name="_range")
     */
    public function rangeAction(string $id, string $range)
    {
        return new Response(sprintf('Range %s for document %s', $range, $id));
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
        return $this->view($this->get('subugoe_iiif.presentation_service')->getCollection($id), Response::HTTP_OK);
    }
}
