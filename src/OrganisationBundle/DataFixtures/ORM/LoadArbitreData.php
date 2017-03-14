<?php

/**
 * Created by PhpStorm.
 * User: pierrebaumes
 * Date: 03/03/2017
 * Time: 14:35
 */

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OrganisationBundle\Entity\Joueur;
use \OrganisationBundle\Entity\Arbitre;

class LoadArbitreData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {

    }
}