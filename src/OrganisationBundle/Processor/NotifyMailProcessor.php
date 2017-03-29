<?php

namespace OrganisationBundle\Processor;

/**
 * Created by PhpStorm.
 * User: pierrebaumes
 * Date: 16/03/2017
 * Time: 11:24
 */
class NotifyMailProcessor
{
    protected $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function notifyEmail($matchs, \FOS\UserBundle\Model\UserInterface $user)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject("Début d'un match")
            ->setFrom('pierre.baumes@epsi.fr')
            ->setTo($user->getEmail())
            ->setBody("Début du match dont l'id est : '".$matchs."'");

        $this->mailer->send($message);
    }
}