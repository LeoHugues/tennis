<?php

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

/**
 * Created by PhpStorm.
 * User: pierrebaumes
 * Date: 16/03/2017
 * Time: 08:58
 */
class MailListener
{
    public function onMatchStart(GetResponseForExceptionEvent $event)
    {

    }
}