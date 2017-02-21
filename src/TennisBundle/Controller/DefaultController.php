<?php

namespace TennisBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use TennisBundle\Controller\JoueurController;
use TennisBundle\TennisBundle;

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
     *
     * Contiendra la liste des joueurs
     */
    public function gestionJoueurAction()
    {
        $joueursRep = $this->getDoctrine()->getRepository('TennisBundle:Joueur');
        $joueurs = $joueursRep->trouverTousJoueur();
        return $this->render('TennisBundle:Default:gestion-joueur.html.twig', array(
            'joueurs' => $joueurs,
        ));
    }

    /**
     * @Route("/gestion-rencontre", name="gestion_rencontre")
     */
    public function gestionRencontreAction()
    {
        return $this->render('TennisBundle:Default:gestion-rencontre.html.twig');
    }
}
