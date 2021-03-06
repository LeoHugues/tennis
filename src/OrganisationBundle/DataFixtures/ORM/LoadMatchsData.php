<?php

/**
 * Created by PhpStorm.
 * User: pierrebaumes
 * Date: 03/03/2017
 * Time: 14:35
 */

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use OrganisationBundle\Entity\Matchs;
use OrganisationBundle\Entity\Equipe;
use OrganisationBundle\Entity\Incident;
use OrganisationBundle\Entity\Joueur;

class LoadMatchsData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $match = new Matchs();

        $date = new DateTime('2008-02-19');

        $match->setDate($date);
        $match->setTerrain($this->getReference('Roland-Garros'));
        $match->setNvxCompet('quart');
        $match->setNbSets(4);
        $match->setServicePremier(3);

        $joueur = new Joueur();

        $joueur->setNom('Nadal');
        $joueur->setPrenom('Raphael');
        $joueur->setNbVictoire(15);

        $joueur2  = new Joueur();
        $incident = new Incident();

        $incident->setMotif('comportement');
        $dateDeb = new DateTime('2016-03-14');
        $dateFin = new DateTime('2016-03-14');
        $dateDeb->format('d-m-Y');
        $dateFin->format('d-m-Y');
        $incident->setDatetimeDeb($dateDeb);

        $joueur2->setNom('Federer');
        $joueur2->setPrenom('Roger');
        $joueur2->setNbVictoire(11);
        $joueur2->addIncident($incident);

        $joueur3 = new Joueur();

        $joueur3->setNom('Monfils');
        $joueur3->setPrenom('Gael');
        $joueur3->setNbVictoire(20);

        $joueur4 = new Joueur();

        $joueur4->setNom('Williams');
        $joueur4->setPrenom('Serena');
        $joueur4->setNbVictoire(13);

        $equipe  = new Equipe();
        $equipe2 = new Equipe();

        $equipe->setJoueur1($joueur);
        $equipe->setJoueur2($joueur2);

        $equipe2->setJoueur1($joueur3);
        $equipe2->setJoueur2($joueur4);

        $match->setEquipes1($equipe);
        $match->setEquipes2($equipe2);

        $user = new \UserBundle\Entity\User();

        $user->setUsername('Tartanpion');
        $user->setEmail('tartanpion@gmail.com');
        $user->setPassword('password');

        $match->setArbitre($user);

        $manager->persist($user);
        $manager->persist($match);
        $manager->flush();
    }

    public function getOrder()
    {
        return 8;
    }
}