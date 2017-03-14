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

class LoadMatchsData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $match = new Matchs();

        $date = new DateTime('2008-02-19');

        $match->setDate($date);

        $manager->persist($match);
        $manager->flush();
    }

    public function getOrder()
    {
        return 6;
    }
}