<?php

namespace OrganisationBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Created by PhpStorm.
 * User: pierrebaumes
 * Date: 29/03/2017
 * Time: 17:02
 */
class CreateMatchEvent extends Event
{
    private $match;

    /**
     * @return mixed
     */
    public function getMatch()
    {
        return $this->match;
    }

    /**
     * @param mixed $match
     */
    public function setMatch($match)
    {
        $this->match = $match;
    }
}