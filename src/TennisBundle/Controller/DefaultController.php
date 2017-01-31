<?php

namespace TennisBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="tennis_home")
     */
    public function indexAction()
    {
        return $this->render('TennisBundle:Default:index.html.twig');
    }

    /**
     * @Route("/gestion-joueur", name="gestion_joueur")
     */
    public function gestionJoueurAction()
    {
        return $this->render('TennisBundle:Default:gestion-joueur.html.twig');
    }

    /**
     * @Route("/gestion-rencontre", name="gestion_rencontre")
     */
    public function gestionRencontreAction()
    {
        return $this->render('TennisBundle:Default:gestion-rencontre.html.twig');
    }
}
