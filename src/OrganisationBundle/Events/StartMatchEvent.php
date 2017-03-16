<?php

/**
 * Created by PhpStorm.
 * User: pierrebaumes
 * Date: 16/03/2017
 * Time: 09:13
 */

use Symfony\Component\EventDispatcher\Event;

class StartMatchEvent extends Event
{
    protected $matchs;

    public function __construct(\OrganisationBundle\Entity\Matchs $matchs)
    {
        $this->matchs = $matchs;
    }

    public function getMatchs()
    {
        return $this->matchs;
    }
}