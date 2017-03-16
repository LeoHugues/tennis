<?php

namespace OrganisationBundle\Controller;


use OrganisationBundle\Entity\Matchs;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArbitreController extends Controller
{    
    /**
     * @Route("arbitre/index-arbitre", name="tennis_arbitre_home")
     */
    public function indexAction()
    {
        $arbitre = $this->getUser();
        $matchs = [];
        $today = new \DateTime();
        /** @var Matchs $match */
        foreach ($arbitre->getMatchs() as $match) {
            if ( $match->getDate() > $today) {
                $matchs[] = $match;
            }
        }

        return $this->render('OrganisationBundle:Default:gestion-rencontre.html.twig', array('matchs' => $matchs));
    }

    /**
     * @Route("arbitre/lancer-rencontre/{idRencontre}", name="tennis_arbitre_lancer_rencontre")
     */
    public function lancerRencontreAction(Request $request, $idRencontre)
    {
        $rencontre = $this->getDoctrine()->getManager()->getRepository('OrganisationBundle:Matchs')->find($idRencontre);
        return $this->render('OrganisationBundle:Default:rencontre.html.twig', array('rencontre' => $rencontre));
    }

    
}