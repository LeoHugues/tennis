<?php

namespace OrganisationBundle\Controller;

use OrganisationBundle\Form\ImportRencontreType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use OrganisationBundle\Repository\JoueurRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use OrganisationBundle\Entity\Document;
use OrganisationBundle\Form\TerrainType;
use organisationBundle\OrganisationBundle;
use OrganisationBundle\Entity\Matchs;
use OrganisationBundle\Entity\Joueur;
use OrganisationBundle\Entity\Equipe;
use OrganisationBundle\Entity\Terrain;
use Userbundle\Entity\User;
use OrganisationBundle\Form\RencontreType;
use OrganisationBundle\Controller\JoueurController;
use Symfony\Component\Validator\Constraints\Date;

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
     * @Route("/organisation/edit-match/{id}", name="edit_match")
     */
    public function editMatchAction(Request $request, $id)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('OrganisationBundle:Matchs');
        $match      = $repository->find($id);

        if(!empty($match)) {

            $form = $this->createForm(RencontreType::class, $match);

            $form->add(
                'submit',
                SubmitType::class,
                array(
                    'label'     => 'Créer',
                    'attr'      => array(
                        'class' => 'col-sm-4 col-sm-offset-3 btn btn-success'
                    )
                )
            );

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                /** @var Matchs $match */
                $match = $form->getData();
                $em    = $this->getDoctrine()->getEntityManager();
                $em->persist($match);
                $em->flush();
            }

            return $this->render('OrganisationBundle:Match:index.html.twig', array('form' => $form->createView()));
        }
    }

    /**
     * @Route("/organisation/remove-match/{id}", name="remove_match")
     * @param int $id_match
     */
    public function removeMatchAction(Request $request, $id)
    {
        $em         = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getManager()->getRepository('OrganisationBundle:Matchs');
        $match      = $repository->find($id);

        if(!empty($match)) {
            $em->remove($match);
            $em->flush();
        }

        $matchs = $repository->findAllMatchs();

        return $this->render('OrganisationBundle:Match:liste-matchs.html.twig', array(
            'matchs' => $matchs,
        ));
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
        $stats = $this->get('tennis.stat.manager')->getStats($match);
        $nbSetE1 = $stats[0];
        $nbBreakE1 = $stats[1];
        $nbMatchE1 = $stats[2];
        $nbBlancE1 = $stats[3];
        $nbSetE2 = $stats[4];
        $nbBreakE2 = $stats[5];
        $nbMatchE2 = $stats[6];
        $nbBlancE2 = $stats[7];
        return $this->render('OrganisationBundle:Match:voir-match.html.twig',
            array(
                'match'     => $match,
                'nbSetE1'   => $nbSetE1,
                'nbBreakE1' => $nbBreakE1,
                'nbMatchE1' => $nbMatchE1,
                'nbBlancE1' => $nbBlancE1,
                'nbSetE2'   => $nbSetE2,
                'nbBreakE2' => $nbBreakE2,
                'nbMatchE2' => $nbMatchE2,
                'nbBlancE2' => $nbBlancE2));
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
            $fileName = md5(uniqid()).'.csv';
            $csv = $form->get('file')->getData()->move("upload", $fileName);
            $path = $csv->getRealPath();
            $row = 1;
            $em = $this->getDoctrine()->getEntityManager();
            if (($handle = fopen($path, "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                    if($row != 1) {
                        $nomJ1_1 = $data[0];
                        $prenomJ1_1 = $data[1];
                        $naissanceJ1_1 = new \DateTime($data[2]);
                        $classementJ1_1 = $data[3];
                        $nationaliteCodeJ1_1 = $data[4];
                        $nationaliteJ1_1 = $em->getRepository('OrganisationBundle:Pays')->findOneby(array('code' => $nationaliteCodeJ1_1));
                            $joueur1_1 = new Joueur();
                            $joueur1_1->setNom($nomJ1_1);
                            $joueur1_1->setPrenom($prenomJ1_1);
                            $joueur1_1->setNaissance($naissanceJ1_1);
                            $joueur1_1->setclassement($classementJ1_1);
                            $joueur1_1->setNationalite($nationaliteJ1_1);
                        $J1_1 = $em->getRepository('OrganisationBundle:Joueur')->findOneby(array('nom' => $nomJ1_1, 'prenom' => $prenomJ1_1));
                        if ($J1_1 == null) {
                            $em->persist($joueur1_1);
                        }else{
                            $joueur1_1 = $J1_1;
                        }
                        $nomJ1_2 = $data[5];
                        $prenomJ1_2 = $data[6];
                        $naissanceJ1_2 = new \DateTime($data[7]);
                        $classementJ1_2 = $data[8];
                        $nationaliteCodeJ1_2 = $data[9];
                        $nationaliteJ1_2 = $em->getRepository('OrganisationBundle:Pays')->findOneby(array('code' => $nationaliteCodeJ1_2));
                        $joueur1_2 = new Joueur();
                        $joueur1_2->setNom($nomJ1_2);
                        $joueur1_2->setPrenom($prenomJ1_2);
                        $joueur1_2->setNaissance($naissanceJ1_2);
                        $joueur1_2->setclassement($classementJ1_2);
                        $joueur1_2->setNationalite($nationaliteJ1_2);
                        $J1_2 = $em->getRepository('OrganisationBundle:Joueur')->findOneby(array('nom' => $nomJ1_2, 'prenom' => $prenomJ1_2));
                        if($J1_2 == null){
                            if($joueur1_2->getNom() != "") {
                                $em->persist($joueur1_2);
                            }
                        }else{
                            $joueur1_2 = $J1_2;
                        }
                        $equipe1 = new Equipe();
                        $equipe1->setJoueur1($joueur1_1);
                        if($joueur1_2->getNom() !== ""){
                            $equipe1->setJoueur2($joueur1_2);
                        }

                        $nomJ2_1 = $data[10];
                        $prenomJ2_1 = $data[11];
                        $naissanceJ2_1 = new \DateTime($data[12]);
                        $classementJ2_1 = $data[13];
                        $nationaliteCodeJ2_1 = $data[14];
                        $nationaliteJ2_1 = $em->getRepository('OrganisationBundle:Pays')->findOneby(array('code' => $nationaliteCodeJ2_1));
                        $joueur2_1 = new Joueur();
                        $joueur2_1->setNom($nomJ2_1);
                        $joueur2_1->setPrenom($prenomJ2_1);
                        $joueur2_1->setNaissance($naissanceJ2_1);
                        $joueur2_1->setclassement($classementJ2_1);
                        $joueur2_1->setNationalite($nationaliteJ2_1);
                        $J2_1 = $em->getRepository('OrganisationBundle:Joueur')->findOneby(array('nom' => $nomJ2_1, 'prenom' => $prenomJ2_1));
                        if($J2_1 == null){

                                $em->persist($joueur2_1);

                        }else{
                            $joueur2_1 = $J2_1;
                        }
                        $nomJ2_2 = $data[15];
                        $prenomJ2_2 = $data[16];
                        $naissanceJ2_2 = new \DateTime($data[17]);
                        $classementJ2_2 = $data[18];
                        $nationaliteCodeJ2_2 = $data[19];
                        $nationaliteJ2_2 = $em->getRepository('OrganisationBundle:Pays')->findOneby(array('code' => $nationaliteCodeJ2_2));

                        $joueur2_2 = new Joueur();
                        $joueur2_2->setNom($nomJ2_2);
                        $joueur2_2->setPrenom($prenomJ2_2);
                        $joueur2_2->setNaissance($naissanceJ2_2);
                        $joueur2_2->setclassement($classementJ2_2);
                        $joueur2_2->setNationalite($nationaliteJ2_2);
                        $J2_2 = $em->getRepository('OrganisationBundle:Joueur')->findOneby(array('nom' => $nomJ2_2, 'prenom' => $prenomJ2_2));
                        if($J2_2 == null){
                            if($joueur2_2->getNom() != "") {
                                $em->persist($joueur2_2);
                            }
                        }else{
                            $joueur2_2 = $J2_2;
                        }
                        $equipe2 = new Equipe();
                        $equipe2->setJoueur1($joueur2_1);
                        if($joueur2_2->getNom() !== ""){
                            $equipe2->setJoueur2($joueur2_2);
                        }

                        $emailArbitre = $data[21];
                        $usernameArbitre = $data[20];
                        $mdpArbitre = $data[22];
                        $arbitre = new User();
                        $arbitre->setEmail($emailArbitre);
                        $arbitre->setUsername($usernameArbitre);
                        $arbitre->setPlainPassword($mdpArbitre);
                        $arbitre->setEnabled(true);
                        $arbitre->addRole('ROLE_ARBITRE');
                        $arb = $em->getRepository('UserBundle:User')->findOneby(array('username' => $usernameArbitre));
                        if($arb == null){
                            $em->persist($arbitre);
                        }else{
                            $arbitre = $arb;
                        }


                        $nomTerrain = $data[23];
                        $terrain = new Terrain();
                        $terrain->setNom($nomTerrain);
                        $ter = $em->getRepository('OrganisationBundle:Terrain')->findOneby(array('nom' => $nomTerrain));
                        if($ter == null){
                            $em->persist($terrain);
                        }else{
                            $terrain = $ter;
                        }

                        $nbSet = $data[24];
                        $nvxCompet = $data[25];
                        $dateMatch = new \DateTime($data[26]);
                        $match = new Matchs();
                        $match->setDate($dateMatch);
                        $match->setEquipes1($equipe1);
                        $match->setEquipes2($equipe2);
                        $match->setNbSets($nbSet);
                        $match->setNvxCompet($nvxCompet);
                        $match->setTerrain($terrain);
                        $match->setArbitre($arbitre);
                        $em->persist($match);
                    }
                    $row++;

                }
                fclose($handle);
                $em->flush();
            }
            $matchsRep = $this->getDoctrine()->getRepository('OrganisationBundle:Matchs');
            $matchs = $matchsRep->findAllMatchs();
            return $this->render('OrganisationBundle:Match:liste-matchs.html.twig', array(
                'matchs' => $matchs,
            ));
        }

        return $this->render('OrganisationBundle:Match:import-match.html.twig', array('form' => $form->createView()));
    }




public function createMatchByCSV(){

    }

}
