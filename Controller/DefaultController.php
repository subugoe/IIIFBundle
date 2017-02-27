<?php

namespace Subugoe\IIIFBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SubugoeIIIFBundle:Default:index.html.twig');
    }
}
