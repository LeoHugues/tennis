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
use \OrganisationBundle\Entity\Point;

class LoadPointData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $point = new Point();

        $dateDeb = new DateTime('2016-02-19 16:30:00');
        $dateFin = new DateTime('2016-02-19 18:30:00');
        $point->setDatetimeDeb($dateDeb);
        $point->setDatetimeFin($dateFin);

        $this->addReference('Point', $point);

        $manager->persist($point);
        $manager->flush();
    }

    public function getOrder()
    {
        return 6;
    }
}