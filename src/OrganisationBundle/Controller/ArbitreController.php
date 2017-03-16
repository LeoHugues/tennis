<?php

namespace OrganisationBundle\Controller;


use OrganisationBundle\Entity\Matchs;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;

class ArbitreController extends Controller
{    
    /**
     * @Route("arbitre/index-arbitre", name="tennis_arbitre_home")
     */
    public function indexAction()
    {
        $arbitre = $this->getUser();
        $matchs = [];
        /** @var Matchs $match */
        foreach ($arbitre->getMatchs() as $match) {
            if ( $match->getDate() > new DateTime()) {
                $matchs[] = $match;
            }
        }
        
        return $this->render('OrganisationBundle:Default:gestion-rencontre.html.twig', array('matchs' => $matchs));
    }
    
    
}