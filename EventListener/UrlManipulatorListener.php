<?php

namespace Subugoe\IIIFBundle\EventListener;

use Subugoe\IIIFBundle\Controller\ImageController;
use Subugoe\IIIFBundle\Controller\PresentationController;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Routing\RouterInterface;

/**
 * Manipulates the generated URL schemes etc.
 */
class UrlManipulatorListener
{
    /**
     * @var array
     */
    private $imageConfiguration;

    /**
     * @var array
     */
    private $presentationConfiguration;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * UrlManipulatorListener constructor.
     *
     * @param array           $imageConfiguration
     * @param array           $presentationConfiguration
     * @param RouterInterface $router
     */
    public function __construct(array $imageConfiguration, array $presentationConfiguration, RouterInterface $router)
    {
        $this->imageConfiguration = $imageConfiguration;
        $this->presentationConfiguration = $presentationConfiguration;
        $this->router = $router;
    }

    public function onKernelController(ControllerEvent $event)
    {
        $controller = $event->getController();

        if (!is_array($controller)) {
            return;
        }

        if ($controller[0] instanceof PresentationController) {
            $this->router->getContext()->setScheme($this->presentationConfiguration['http']['scheme']);
        }

        if ($controller[0] instanceof ImageController) {
            $this->router->getContext()->setScheme($this->imageConfiguration['http']['scheme']);
        }
    }
}
