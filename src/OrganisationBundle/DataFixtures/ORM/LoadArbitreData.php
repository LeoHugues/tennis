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
use OrganisationBundle\Entity\Arbitre;

class LoadArbitreData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $arbitre = new Arbitre();
        $arbitre->setUsername('Ali Nili');
        $arbitre->setEmail('ali.nili@gmail.com');
        $arbitre->setPassword('test');

        $arbitre2 = new Arbitre();
        $arbitre2->setUsername('Bob Strachan');
        $arbitre2->setEmail('bob.strachan@gmail.com');
        $arbitre2->setPassword('test');

        $arbitre3 = new Arbitre();
        $arbitre3->setUsername('Carlos Ramos');
        $arbitre3->setEmail('carlos.ramos@gmail.com');
        $arbitre3->setPassword('test');

        $arbitre4 = new Arbitre();
        $arbitre4->setUsername('Cédric Mourier');
        $arbitre4->setEmail('cedric.mourier@gmail.com');
        $arbitre4->setPassword('test');

        $this->addReference('Nili', $arbitre);
        $this->addReference('Strachan', $arbitre2);
        $this->addReference('Ramos', $arbitre3);
        $this->addReference('Mourier', $arbitre4);

        $manager->persist($arbitre);
        $manager->persist($arbitre2);
        $manager->persist($arbitre3);
        $manager->persist($arbitre4);
        $manager->flush();
    }

    public function getOrder()
    {
        return 5;
    }
}