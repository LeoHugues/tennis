<?php

/**
 * Created by PhpStorm.
 * User: pierrebaumes
 * Date: 25/04/2017
 * Time: 11:05
 */

namespace OrganisationBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class StartMatchEvent extends Event
{
    /** @var string */
    protected $match = null;

    public function setMatch($match)
    {
        $this->match = $match;
    }

    public function getMatch()
    {
        return $this->match;
    }
}