<?php

namespace TennisBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use TennisBundle\Entity\Matchs;
use TennisBundle\Form\RencontreType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="tennis_home")
     */
    public function indexAction(Request $request)
    {
        $match = new Matchs();
        $form = $this->createForm(RencontreType::class, $match);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
        }
        return $this->render('TennisBundle:Default:index.html.twig', array('form' => $form->createView()));
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
