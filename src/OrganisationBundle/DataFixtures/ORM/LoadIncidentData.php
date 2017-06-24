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
use OrganisationBundle\Entity\Incident;

class LoadIncidentData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $incident = new Incident();


        $incident->setMotif('Doute sur la position de la trace de la balle.');
        $dateDeb = new DateTime('2016-02-19 16:45:00');
        $incident->setDatetimeDeb($dateDeb);

        $this->addReference('Incident', $incident);

        $manager->persist($incident);
        $manager->flush();
    }

    public function getOrder()
    {
        return 7;
    }
}