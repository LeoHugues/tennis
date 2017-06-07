<?php

namespace OrganisationBundle\Controller;

use OrganisationBundle\Form\ImportRencontreType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use OrganisationBundle\Entity\Terrain;
use OrganisationBundle\Form\TerrainType;
use organisationBundle\OrganisationBundle;
use OrganisationBundle\Entity\Matchs;
use OrganisationBundle\Form\RencontreType;
use OrganisationBundle\Controller\JoueurController;

class MatchController extends Controller
{
    /**
     * @Route("/organisation/create-match", name="create_match")
     */
    public function createMatchAction(Request $request)
    {
        $match = new Matchs();
        $form = $this->createForm(RencontreType::class, $match);
        $form->add('submit', SubmitType::class, array(
            'label' => 'Créer',
            'attr' => array(
                'class' => 'col-sm-4 col-sm-offset-3 btn btn-success'
            )
        ));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Matchs $match */
            $match = $form->getData();

            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($match);
            $em->flush();
        }

        return $this->render('OrganisationBundle:Match:index.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/liste-match", name="list_all_match")
     *
     * Contiendra la liste des matchs
     */
    public function listMatchAction(){
        $matchsRep = $this->getDoctrine()->getRepository('OrganisationBundle:Matchs');
        $matchs = $matchsRep->findAllMatchs();
        return $this->render('OrganisationBundle:Match:liste-matchs.html.twig', array(
            'matchs' => $matchs,
        ));
    }

    /**
    * @Route("/voir-match/{id_match}", name="voir-match")
    * @param int $id_match
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function voirMatchAction($id_match){
        $match = $this->getDoctrine()->getManager()->find(Matchs::class, $id_match);
        return $this->render('OrganisationBundle:Match:voir-match.html.twig',
            array('match' => $match));
    }

    /**
     * @Route("/modifier-match/{id_match}", name="modifier-match")
     * @param int $id_match
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function modifMatchAction($id_match){
        $match = $this->getDoctrine()->getManager()->find(Matchs::class, $id_match);
        return $this->render('OrganisationBundle:Match:modifier-match.html.twig',
                array('match' => $match));
    }

    /**
     * @Route("/organisation/import-match", name="import_matchs")
     */
    public function importMatchAction(Request $request)
    {
        $form = $this->createForm(ImportRencontreType::class);
        $form->add('submit', SubmitType::class, array(
            'label' => 'Importer',
            'attr' => array(
                'class' => 'col-sm-4 col-sm-offset-3 btn btn-success'
            )
        ));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['fichier']->getData();
            $fileName = md5(uniqid()).'.csv';
            $file->move(
                "/web/upload",
                $fileName
            );
            //$csv = new SplFileObject('web/upload/'+$fileName, 'r');
            //$csv->setFlags(SplFileObject::READ_CSV);
        }

        return $this->render('OrganisationBundle:Match:import-match.html.twig', array('form' => $form->createView()));
    }

    public function createMatchByCSV(){

    }

}
