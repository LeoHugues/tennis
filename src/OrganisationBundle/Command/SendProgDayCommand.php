<?php
/**
 * Created by PhpStorm.
 * User: pierrebaumes
 * Date: 25/06/2017
 * Time: 15:45
 */
namespace OrganisationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Constraints\DateTime;

class SendProgDayCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('sendProgDay')
            ->setDescription('Send the programming of the day');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("======> Début de l'execution de la commande.");

        $dateDeb = new \DateTime();
        $dateDeb->setTime(0, 1);

        $dateFin = new \DateTime();
        $dateFin->setTime(23, 59);

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $repoMatch = $em->getRepository('OrganisationBundle:Matchs');
        $repoUsers = $em->getRepository('UserBundle:User');

        $matchs = $repoMatch->findMatchsOfDay($dateDeb, $dateFin);
        $users  = $repoUsers->getUsersOrga('ROLE_USER');

        $mails = array();

        foreach($users as $user) {
            $mails[] = $user->getEmail();
        }

        $message = new \Swift_Message();

        $message->setSubject('Programmation de la journée')
                ->setFrom('pierre.baumes@epsi.fr');

        $str = null;
        foreach($matchs as $match) {

            $time = $match->getDate();
            $joueur1 = $match->getEquipes1()->getJoueur1()->getPrenom() . ' ' . $match->getEquipes1()->getJoueur1()->getNom();
            $joueur2 = $match->getEquipes2()->getJoueur1()->getPrenom() . ' ' . $match->getEquipes2()->getJoueur1()->getNom();

            if(!empty($match->getEquipes1()->getJoueur2) and !empty($match->getEquipes2()->getJoueur2())) {
                $joueur3 = $match->getEquipes1()->getJoueur2()->getPrenom() . ' ' . $match->getEquipes1()->getJoueur2()->getNom();
                $joueur4 = $match->getEquipes2()->getJoueur2()->getPrenom() . ' ' . $match->getEquipes2()->getJoueur2()->getNom();
                $str = $str . 'Match se jouant le terrain : ' . $match->getTerrain() . ' opposant ' . $joueur1 . ' avec ' . $joueur2 . ' contre ' . $joueur3 . ' avec ' . $joueur4 . ' démarre à : ' . date_format($time, 'H:i') . "\n" ;
            }

            $str = $str . 'Match se jouant sur le terrain : ' . $match->getTerrain() . ' opposant ' . $joueur1 . ' et ' . $joueur2 . ' démarre à : ' . date_format($time, 'H:i') . "\n";
        }

        $message->setBody("Programmation de la journée : " . $str);

        foreach($mails as $mail) {
            $message->addCc($mail);
        }

        $this->getContainer()->get('mailer')->send($message);

        $output->writeln("======> Fin de la commande, emails envoyés avec succès.");
    }
}