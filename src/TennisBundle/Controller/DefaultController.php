<?php

namespace TennisBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use TennisBundle\Entity\Matchs;
use TennisBundle\Form\RencontreType;
use TennisBundle\Controller\JoueurController;
use TennisBundle\TennisBundle;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="tennis_home")
     */
    public function indexAction(Request $request)
    {
        $match = new Matchs();
        $form = $this->createForm(RencontreType::class, $match);
        $form->add('submit', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
        }
        return $this->render('TennisBundle:Default:index.html.twig', array('form' => $form->createView()));
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
