<?php

/**
 * Created by PhpStorm.
 * User: pierrebaumes
 * Date: 25/04/2017
 * Time: 11:11
 */

namespace OrganisationBundle\EventListener;

use OrganisationBundle\OrgaEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use OrganisationBundle\Event\StartMatchEvent;

class StartMatchSuscriber implements EventSubscriberInterface
{
    protected $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        // Liste des évènements écoutés et méthodes à appeler
        return array(
            OrgaEvents::START_MATCH => 'sendMail'
        );
    }

    public function sendMail(StartMatchEvent $event)
    {
        $match = $event->getMatch();

        $message = \Swift_Message::newInstance()
            ->setSubject('Début du match dont l\'id est : ' . $match->getId())
            ->setFrom('pierre.baumes@epsi.fr')
            ->setTo('pierre.baumes@epsi.fr')
            ->setBody('Match débuté !', 'text/html');

        $this->mailer->send($message);
    }
}