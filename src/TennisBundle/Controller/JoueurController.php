<?php

namespace TennisBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TennisBundle\Entity\Joueur;

class JoueurController extends Controller
{
    /**
     * @Route("/gestion-joueur/créer-joueur", name="gestion-joueur_creer-joueur")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function creerJoueurAction(Request $request)
    {
        // 1) build the form
        $joueur = new Joueur();
        $form = $this->createForm('TennisBundle\Form\Gestion_joueur\CreerJoueurFormType', array('$class' => 'Joueur'));

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $nom = $form['nom']->getData();
            $prenom = $form['prenom']->getData();
            $joueur->setNom($nom);
            $joueur->setPrenom($prenom);

            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($joueur);
            $em->flush();

            return $this->redirectToRoute('gestion_joueur');
        }

        return $this->render(
            'TennisBundle:Gestion_joueur:creer.html.twig',
            array('form' => $form->createView())
        );
    }



}
