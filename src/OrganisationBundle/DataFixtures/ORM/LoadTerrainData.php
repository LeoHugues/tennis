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
use OrganisationBundle\Entity\Terrain;

class LoadTerrainData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $terrain = new Terrain();
        $terrain->setNom('Wimbledon');

        $terrain2 = new Terrain();
        $terrain2->setNom('Roland-Garros');

        $terrain3 = new Terrain();
        $terrain3->setNom('Lotus Court');

        $terrain4 = new Terrain();
        $terrain4->setNom('Agora');

        $manager->persist($terrain);
        $manager->persist($terrain2);
        $manager->persist($terrain3);
        $manager->persist($terrain4);
        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }
}