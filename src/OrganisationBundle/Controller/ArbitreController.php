<?php

namespace OrganisationBundle\Controller;


use OrganisationBundle\Entity\Matchs;
use OrganisationBundle\EventListener\StartMatchSuscriber;
use OrganisationBundle\OrgaEvents;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use OrganisationBundle\Event\StartMatchEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
        $em = $this->getDoctrine()->getManager();

        $rencontre = $em->getRepository('OrganisationBundle:Matchs')->find($idRencontre);
        $rencontre->setStatus(Matchs::MATCHE_EN_COURS);
        $em->persist($rencontre);
        $em->flush();

        $mailer    = $this->get('mailer');

        $repository = $em->getRepository('UserBundle:User');
        $usersOrga  = $repository->getUsersOrga('ROLE_ORGA'); // C'est pas très sexy saaaooh laaaaoh

        $event = new StartMatchEvent();
        $event->setMatch($rencontre);

        $dispatcher = new EventDispatcher();
        $subscriber = new StartMatchSuscriber($mailer, $usersOrga);
        $dispatcher->addSubscriber($subscriber);

        $dispatcher->dispatch(OrgaEvents::START_MATCH, $event);

        return $this->render('OrganisationBundle:Default:rencontre.html.twig', array('rencontre' => $rencontre));
    }
}