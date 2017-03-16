<?php

namespace OrganisationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use OrganisationBundle\Entity\Terrain;
use OrganisationBundle\Form\TerrainType;
use organisationBundle\OrganisationBundle;
use OrganisationBundle\Entity\Matchs;
use OrganisationBundle\Form\RencontreType;
use OrganisationBundle\Controller\JoueurController;

class DefaultController extends Controller
{
    /**
     * @Route("/organisation", name="tennis_organisation_home")
     */
    public function indexAction(Request $request)
    {
        $match = new Matchs();
        $form = $this->createForm(RencontreType::class, $match);
        $form->add('submit', SubmitType::class, array(
            'label' => 'Commencer la rencontre',
            'attr' => array(
                'class' => 'col-sm-4 col-sm-offset-3 btn btn-default'
            )
        ));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Matchs $match */
            $match = $form->getData();
            $match->setDate(new \DateTime());

            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($match);
            $em->flush();
        }

        return $this->render('OrganisationBundle:Default:index.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/admin", name="admin_home")
     */
    public function indexAdminAction(Request $request)
    {
        $match = new Matchs();
        $form = $this->createForm(RencontreType::class, $match);
        $form->add('submit', SubmitType::class, array(
            'label' => 'Commencer la rencontre',
            'attr' => array(
                'class' => 'col-sm-4 col-sm-offset-3 btn btn-default'
            )
        ));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Matchs $match */
            $match = $form->getData();
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($match);
            $em->flush();

            $this->addFlash('info', 'La rencontre a bien été créé');
        }
        return $this->render('OrganisationBundle:Default:index.html.twig', array('form' => $form->createView()));
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
     * @Route("/gestion-terrain", name="gestion_terrain")
     */
    public function gestionTerrainAction(Request $request)
    {
        $terrain = new Terrain();
        $form = $this->createForm(TerrainType::class, $terrain);
        $form->add('submit', SubmitType::class, array(
            'label' => 'Validez',
            'attr' => array(
                'class' => 'col-sm-4 col-sm-offset-3 btn btn-default'
            )
        ));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $terrain = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($terrain);
            $em->flush();
        }
        return $this->render('OrganisationBundle:Default:gestion-terrain.html.twig', array('form' => $form->createView()));
    }
}
