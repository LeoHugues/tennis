<?php

namespace FrontEndBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="tennis_home")
     */
    public function indexAction()
    {
        return $this->render('FrontEndBundle:Default:index.html.twig');
    }
}
