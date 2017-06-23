<?php
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 2/21/17
 * Time: 12:20 PM
 */

namespace OrganisationBundle\Controller;


use FOS\RestBundle\Controller\Annotations\Route;
use Negotiation\Match;
use OrganisationBundle\Entity\Avertissement;
use OrganisationBundle\Entity\Incident;
use OrganisationBundle\Entity\Matchs;
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
        $score        = $pointManager->addPoint($idRencontre, $idEquipe);
        $em           = $this->getDoctrine()->getManager();
        $repository   = $em->getRepository('UserBundle:User');
        $usersOrga    = $repository->getUsersOrga('ROLE_ORGA');
        $emails       = array();
        $em           = $this->getDoctrine()->getManager();
        $repoMatch    = $em->getRepository('OrganisationBundle:Matchs');
        $match        = $repoMatch->find($idRencontre);
        $terrain      = $match->getTerrain();

        if ($score['termine']) {
            $em = $this->getDoctrine()->getEntityManager();
            $rencontre = $em->getRepository('OrganisationBundle:Matchs')->find($idRencontre);
            $rencontre->setTermine(true);
            $em->persist($rencontre);
            $em->flush();

            foreach($usersOrga as $user) {
                $emails[] = $user->getEmail();
            }

            $message = \Swift_Message::newInstance()
                ->setSubject("Fin de match")
                ->setFrom('p.baumes@gmail.com')
                ->setTo($emails)
                ->setBody('Fin du match : ' . $terrain->getNom() . " se jouant le : " . $match->getDate()->format('d-m-Y H:i:s'));

            $this->get('mailer')->send($message);
        }

        if($score['point']['equipe1'] == 0 and $score['jeu']['equipe1'] == 0 and $score['point']['equipe2'] == 0 and $score['jeu']['equipe1'] == 0) {

            foreach($usersOrga as $user) {
                $emails[] = $user->getEmail();
            }

            $message = \Swift_Message::newInstance()
                ->setSubject("Début d'un set")
                ->setFrom('p.baumes@gmail.com')
                ->setTo($emails)
                ->setBody('Nouveau set !');

            $this->get('mailer')->send($message);
        }

        return new JsonResponse($score);
    }

    /**
     *
     * @Route("/treat/{idRencontre}/{idJoueur}", name="call_therapist")
     */
    public function AjaxCallTherapist(Request $request, $idRencontre, $idJoueur)
    {
        $em         = $this->getDoctrine()->getManager();
        $repoUser   = $em->getRepository('UserBundle:User');
        $repoJoueur = $em->getRepository('OrganisationBundle:Joueur');
        $repoMatch  = $em->getRepository('OrganisationBundle:Matchs');

        $usersOrga  = $repoUser->getUsersOrga('ROLE_ORGA');
        $joueur     = $repoJoueur->find($idJoueur);
        $match      = $repoMatch->find($idRencontre);

        $emails     = array();

        foreach($usersOrga as $user) {
            $emails[] = $user->getEmail();
        }

        $message = \Swift_Message::newInstance()
            ->setSubject("Soigneur applé pour le joueur : " . $joueur->getPrenom() . ' ' . $joueur->getNom())
            ->setFrom('p.baumes@gmail.com')
            ->setTo($emails)
            ->setBody("Appel d'un soigneur lors du match opposant " . $match->getEquipes1()->getJoueur1() . ' et ' . $match->getEquipes2()->getJoueur1() . ' sur le terrain ' . $match->getTerrain());

        $this->get('mailer')->send($message);

        return new JsonResponse($joueur);
    }

    /**
     *
     * @Route("/warning/{idRencontre}/{idJoueur}", name="add_warning")
     */
    public function AjaxWarning(Request $request, $idRencontre, $idJoueur)
    {
        $avertissement = new Avertissement();

        $em        = $this->getDoctrine()->getManager();
        $repoMatch = $em->getRepository('OrganisationBundle:Matchs');
        $match     = $repoMatch->find($idRencontre);

        $repoJoueur = $em->getRepository('OrganisationBundle:Joueur');
        $joueur     = $repoJoueur->find($idJoueur);

        $avertissement->setMatch($match);
        $avertissement->setJoueur($joueur);

        $data = $request->request->get('content');

        $avertissement->setMotif($data);

        $em->persist($avertissement);
        $em->flush();

        return new JsonResponse();
    }

    /**
     *
     * @Route("/incident/{idRencontre}", name="add_incident")
     */
    public function AjaxIncident(Request $request, $idRencontre)
    {
        $incident = new Incident();

        $em         = $this->getDoctrine()->getManager();
        $repoUser   = $em->getRepository('UserBundle:User');
        $repoMatchs = $em->getRepository('OrganisationBundle:Matchs');
        $match      = $repoMatchs->find($idRencontre);
        $terrain    = $match->getTerrain();

        $usersOrga  = $repoUser->getUsersOrga('ROLE_ORGA');

        $incident->setMatch($match);

        $data = $request->request->get('content');

        $incident->setMotif($data);

        $em->persist($incident);
        $em->flush();

        $emails     = array();

        foreach($usersOrga as $user) {
            $emails[] = $user->getEmail();
        }

        $message = \Swift_Message::newInstance()
            ->setSubject("Incident grave déclaré.")
            ->setFrom('p.baumes@gmail.com')
            ->setTo($emails)
            ->setBody("Incident grave déclaré le " . $incident->getDatetimeDeb()->format('d-m-Y H:i:s') . " sur le match se jouant à : " . $terrain->getNom());

        $this->get('mailer')->send($message);

        return new JsonResponse();
    }
}