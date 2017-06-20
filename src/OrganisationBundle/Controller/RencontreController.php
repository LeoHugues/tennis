<?php
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 2/21/17
 * Time: 12:20 PM
 */

namespace OrganisationBundle\Controller;


use FOS\RestBundle\Controller\Annotations\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RencontreController extends Controller
{
    /**
     * Call qui permet à l'arbitre de déterminer le premier serveur
     *
     * @Route("/rencontre/{idMatch}/service/{idEquipe}", name="call_premier_serveur")
     */
    public function AjaxCallPremierService(Request $request, $idMatch, $idEquipe) {
        $rencontre = $this->getDoctrine()->getEntityManager()->getRepository('OrganisationBundle:Matchs')->find($idMatch);
        $rencontre->setServicePremier($idEquipe);

        $em = $this->getDoctrine()->getManager();
        $em->persist($rencontre);
        $em->flush();

        return new Response('success');
    }

    /**
     *
     * @Route("/rencontre/{idRencontre}/addPoint/{idEquipe}", name="call_add_point")
     */
    public function AjaxCallAddPoint(Request $request, $idRencontre, $idEquipe) {
        $pointManager = $this->get('tennis.point.manager');

        $score = $pointManager->addPoint($idRencontre, $idEquipe);

        if ($score['termine']) {
            $em = $this->getDoctrine()->getEntityManager();
            $rencontre = $em->getRepository('OrganisationBundle:Matchs')->find($idRencontre);
            $rencontre->setTermine(true);
            $em->persist($rencontre);
            $em->flush();
        }

        return new JsonResponse($score);
    }
    
}