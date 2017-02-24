<?php

namespace OrganisationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use OrganisationBundle\Entity\Matchs;
use OrganisationBundle\Form\RencontreType;
use OrganisationBundle\Controller\JoueurController;
use OrganisationBundle\OrganisationBundle;

class DefaultController extends Controller
{
    /**
     * @Route("/arbitre", name="tennis_arbitre_home")
     */
    public function indexAction(Request $request)
    {
        $match = new Matchs();
        $form = $this->createForm(RencontreType::class, $match);
        $form->add('submit', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
        }
        return $this->render('OrganisationBundle:Default:index-arbitre.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/gestion-joueur", name="gestion_joueur")
     *
     * Contiendra la liste des joueurs
     */
    public function gestionJoueurAction()
    {
        $joueursRep = $this->getDoctrine()->getRepository('OrganisationBundle:Joueur');
        $joueurs = $joueursRep->trouverTousJoueur();
        return $this->render('OrganisationBundle:Default:gestion-joueur.html.twig', array(
            'joueurs' => $joueurs,
        ));
    }

    /**
     * @Route("/gestion-rencontre", name="gestion_rencontre")
     */
    public function gestionRencontreAction()
    {
        return $this->render('OrganisationBundle:Default:gestion-rencontre.html.twig');
    }
}
