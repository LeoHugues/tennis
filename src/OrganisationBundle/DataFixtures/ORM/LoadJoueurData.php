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
use OrganisationBundle\Entity\Joueur;
use \OrganisationBundle\Entity\Avertissement;

class LoadJoueurData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $joueur = new Joueur();

        $joueur->setNom('Nadal');
        $joueur->setPrenom('Raphael');
        $joueur->setNbVictoire(15);

        $joueur2       = new Joueur();
        $avertissement = new Avertissement();

        $avertissement->setMotif('comportement');
        $date = new DateTime('2016-03-14');
        $date->format('d-m-Y');
        $avertissement->setDatetime($date);

        $joueur2->setNom('Federer');
        $joueur2->setPrenom('Roger');
        $joueur2->setNbVictoire(11);
        $joueur2->addAvertissement($avertissement);

        $joueur3 = new Joueur();

        $joueur3->setNom('Monfils');
        $joueur3->setPrenom('Gael');
        $joueur3->setNbVictoire(20);

        $joueur4 = new Joueur();

        $joueur4->setNom('Williams');
        $joueur4->setPrenom('Serena');
        $joueur4->setNbVictoire(13);

        $joueur5 = new Joueur();

        $joueur5->setNom('Gasquet');
        $joueur5->setPrenom('Richard');
        $joueur5->setNbVictoire(11);

        $joueur6 = new Joueur();

        $joueur6->setNom('Murray');
        $joueur6->setPrenom('Andy');
        $joueur6->setNbVictoire(17);

        $this->addReference('Nadal', $joueur);
        $this->addReference('Federer', $joueur2);
        $this->addReference('Monfils', $joueur3);
        $this->addReference('Williams', $joueur4);
        $this->addReference('Gasquet', $joueur5);
        $this->addReference('Murray', $joueur6);

        $manager->persist($joueur);
        $manager->persist($joueur2);
        $manager->persist($joueur3);
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}