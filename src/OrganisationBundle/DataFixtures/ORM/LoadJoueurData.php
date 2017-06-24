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
use OrganisationBundle\Entity\Incident;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadJoueurData extends AbstractFixture implements OrderedFixtureInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $joueur = new Joueur();

        $joueur->setNom('Nadal');
        $joueur->setPrenom('Raphael');
        $joueur->setNbVictoire(15);
        $joueur->setNaissance('1985/07/03');
        $joueur->setClassement('2');
        $joueur->setNationalite('ESP');

        $joueur2       = new Joueur();
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
        $joueur2->setNaissance('1985/07/03');
        $joueur2->setClassement('3');
        $joueur2->setNationalite('SUI');

        $joueur3 = new Joueur();

        $joueur3->setNom('Monfils');
        $joueur3->setPrenom('Gael');
        $joueur3->setNbVictoire(20);
        $joueur3->setNaissance('1985/07/03');
        $joueur3->setClassement('10');
        $joueur3->setNationalite('FRA');

        $joueur4 = new Joueur();

        $joueur4->setNom('Williams');
        $joueur4->setPrenom('Serena');
        $joueur4->setNbVictoire(13);
        $joueur4->setNaissance('1985/07/03');
        $joueur4->setClassement('50');
        $joueur4->setNationalite('FRA');

        $joueur5 = new Joueur();

        $joueur5->setNom('Gasquet');
        $joueur5->setPrenom('Richard');
        $joueur5->setNbVictoire(11);
        $joueur5->setNaissance('1985/07/03');
        $joueur5->setClassement('11');
        $joueur5->setNationalite('FRA');

        $joueur6 = new Joueur();

        $joueur6->setNom('Murray');
        $joueur6->setPrenom('Andy');
        $joueur6->setNbVictoire(17);
        $joueur6->setNaissance('1985/07/03');
        $joueur6->setClassement('10');
        $joueur6->setNationalite('GBR');

        $this->addReference('Nadal', $joueur);
        $this->addReference('Federer', $joueur2);
        $this->addReference('Monfils', $joueur3);
        $this->addReference('Williams', $joueur4);
        $this->addReference('Gasquet', $joueur5);
        $this->addReference('Murray', $joueur6);

        $manager->persist($joueur);
        $manager->persist($joueur2);
        $manager->persist($joueur3);
        $manager->persist($joueur4);
        $manager->persist($joueur5);
        $manager->persist($joueur6);
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}