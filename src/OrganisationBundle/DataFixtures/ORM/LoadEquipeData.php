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
use \OrganisationBundle\Entity\Equipe;

class LoadEquipeData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $equipe1 = new Equipe();

        $equipe1->setJoueur1($this->getReference('Nadal'));
        $equipe1->setJoueur2($this->getReference('Monfils'));

        $equipe2 = new Equipe();

        $equipe2->setJoueur1($this->getReference('Federer'));
        $equipe2->setJoueur2($this->getReference('Williams'));

        $equipe3 = new Equipe();

        $equipe3->setJoueur1($this->getReference('Gasquet'));
        $equipe3->setJoueur2($this->getReference('Murray'));

        $this->addReference('Nadal-Monfils', $equipe1);
        $this->addReference('Federer-Williams', $equipe2);
        $this->addReference('Gasquet-Murray', $equipe3);

        $manager->persist($equipe1);
        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}