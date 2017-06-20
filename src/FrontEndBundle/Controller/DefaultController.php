<?php

namespace FrontEndBundle\Controller;

use OrganisationBundle\Entity\Matchs;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="tennis_home")
     */
    public function indexAction()
    {
        if(true === $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')){
            $currentUser =  $this->get('security.token_storage')->getToken()->getUser();
            if($currentUser->getRoleString() == 'ROLE_ADMIN'){
                return $this->redirectToRoute('admin_home');
            }
            elseif($currentUser->getRoleString() == 'ROLE_ORGA'){
                return $this->redirectToRoute('tennis_organisation_home');
            }
            elseif($currentUser->getRoleString() == 'ROLE_ARBITRE'){
                return $this->redirectToRoute('tennis_arbitre_home');
            }
        }

        $matchsRep = $this->getDoctrine()->getRepository('OrganisationBundle:Matchs');
        
        $matchsEnCour = $matchsRep->findBy(
            array('status' => Matchs::MATCHE_EN_COURS),
            array('date' => 'DESC')
        );
        $matchsTermine = $matchsRep->findBy(
            array('status' => Matchs::MATCHE_TERMINE),
            array('date' => 'DESC')
        );
        $matchsProgramme = $matchsRep->findBy(
            array('status' => Matchs::MATCHE_PROGRAMME),
            array('date' => 'DESC')
        );
        
        $pointManager = $this->get('tennis.point.manager');
        
        /** @var Matchs $match */
        foreach ($matchsEnCour as $match) {
            $match->setScore($pointManager->getScore($match));
        }

        return $this->render('FrontEndBundle:Default:index.html.twig', array(
            'matchsEnCours'     => $matchsEnCour, 
            'matchsTermine'     => $matchsTermine,
            'matchsProgramme'   => $matchsProgramme,
        ));
    }

}
