<?php
/**
 * Created by PhpStorm.
 * User: pierrebaumes
 * Date: 26/06/2017
 * Time: 15:08
 */

namespace OrganisationBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendConfPressCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('sendConfPress')
            ->setDescription('Send press conference after 30 mintues end of match');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("======> Début de l'execution de la commande.");

        $dateDeb = new \DateTime();
        $dateDeb->setTime(0, 1);

        $dateFin = new \DateTime();
        $dateFin->setTime(23, 59);

        $em        = $this->getContainer()->get('doctrine.orm.entity_manager');
        $repoMatch = $em->getRepository('OrganisationBundle:Matchs');
        $repoUsers = $em->getRepository('UserBundle:User');

        $matchs = $repoMatch->findMatchsByStatus(2, $dateDeb, $dateFin);
        $users  = $repoUsers->getUsersOrga('ROLE_PRESS');

        $mails = array();
        $now   = new \DateTime();

        foreach($users as $user) {
            $mails[] = $user->getEmail();
        }

        $message = \Swift_Message::newInstance()
            ->setSubject('Début de la conférence de presse')
            ->setFrom('pierre.baumes@epsi.fr');

        foreach($matchs as $match) {
            $dateDiff = abs($now->getTimestamp() - $match->getDate()->getTimestamp()) / 60;

            if($dateDiff >= 30 and $dateDiff <= 37) {
                $message->setBody("Début de la conférence de presse pour le match qui s'est déroulé sur le terrain : " . $match->getTerrain() . " terminé à " . $match->getDateFin() . "\n");
            }
        }

        foreach($mails as $mail) {
            $message->addCc($mail);
        }

        $this->getContainer()->get('mailer')->send($message);

        $output->writeln("======> Fin de la commande, emails envoyés avec succès.");
    }
}