<?php
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 2/21/17
 * Time: 12:20 PM
 */

namespace OrganisationBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RencontreController extends Controller
{
    /**
     * Call qui permet à l'arbitre de déterminer le premier serveur
     *
     * @Route("/rencontre/{idMatch}/service/{idEquipe}", name="call_premier_serveur")
     */
    public function AjaxCallPremierService(Request $request, $idMatch, $idEquipe) {

    }
}