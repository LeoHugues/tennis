<?php

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use OrganisationBundle\Entity\Matchs;

/**
 * Created by PhpStorm.
 * User: pierrebaumes
 * Date: 16/03/2017
 * Time: 08:58
 */
class MailListener
{
    protected $mailer;

    public function onMatchStart(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function notifyEmail(Matchs $matchs)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject("Début d'un match")
            ->setFrom('admin@votresite.com')
            ->setTo('admin@votresite.com')
            ->setBody("Le match du '".$matchs->getDate()."' a commencé.");

        $this->mailer->send($message);
    }
}