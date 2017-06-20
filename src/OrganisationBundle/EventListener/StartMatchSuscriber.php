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
    protected $usersOrga;

    public function __construct(\Swift_Mailer $mailer, $usersOrga)
    {
        $this->mailer    = $mailer;
        $this->usersOrga = $usersOrga;
    }

    public static function getSubscribedEvents()
    {
        return array(
            OrgaEvents::START_MATCH => 'sendMail'
        );
    }

    public function sendMail(StartMatchEvent $event)
    {
        $match = $event->getMatch();
        $emails = array();

        foreach($this->usersOrga as $user) {
            $emails[] = $user->getEmail();
        }

        $message = \Swift_Message::newInstance()
            ->setSubject('Début du match dont l\'id est : ' . $match->getId())
            ->setFrom('p.baumes@gmail.com')
            ->setTo($emails)
            ->setBody('Match débuté !');

        $this->mailer->send($message);
    }
}