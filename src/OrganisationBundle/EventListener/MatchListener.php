<?php

namespace OrganisationBundle\Event;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use OrganisationBundle\Processor\NotifyMailProcessor;
use Symfony\Component\HttpFoundation\Response;
use OrganisationBundle\Entity\Matchs;

/**
 * Created by PhpStorm.
 * User: pierrebaumes
 * Date: 16/03/2017
 * Time: 08:58
 */
class MatchListener
{
}