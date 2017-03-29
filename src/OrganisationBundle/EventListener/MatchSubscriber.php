<?php

// src/OrganisationBundle/EventListener/MatchListener.php
namespace OrganisationBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManager;
use OrganisationBundle\Event\CreateMatchEvent;
use OrganisationBundle\MatchEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Created by PhpStorm.
 * User: pierrebaumes
 * Date: 29/03/2017
 * Time: 16:52
 */
class MatchSubscriber implements EventSubscriberInterface
{
    public function __construct()
    {
    }

    public static function getSubscribedEvents()
    {
        // Liste des évènements écoutés et méthodes à appeler
        return array(
            MatchEvents::AFTER_MATCH_CREATED => 'onMatchCreate'
        );
    }

    public function onMatchCreate(CreateMatchEvent $event)
    {
        $match = $event->getMatch();
        //todo : envoyer un email à l'organisation pour le match en question
        
    }
}