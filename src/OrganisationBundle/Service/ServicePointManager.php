<?php

namespace OrganisationBundle\Service;


use Doctrine\ORM\EntityManager;
use OrganisationBundle\Entity\Matchs;
use OrganisationBundle\Entity\Point;

/**
 * Created by PhpStorm.
 * User: leo
 * Date: 3/16/17
 * Time: 10:40 AM
 */
class ServicePointManager
{
    /** @var  EntityManager */
    private $em;

    public function __construct($em)
    {
        $this->em = $em;
    }

    public function addPoint($idRencontre, $idEquipe) {
        $rencontre = $this->em->getRepository('OrganisationBundle:Matchs')->find($idRencontre);
        $equipe = $this->em->getRepository('OrganisationBundle:Equipe')->find($idEquipe);

        $point = new Point();
        $point->setEquipe($equipe);
        $point->setMatch($rencontre);
        $this->em->persist($point);

        $rencontre->addPoint($point);
        $this->em->persist($rencontre);
        $this->em->flush();

        return $this->getScore($rencontre);
    }

    public function getScore(Matchs $match)
    {//dump($match->getPoints()->count());die;
        $equipe1 = array('set' => 0, 'jeu' => 0, 'point' => 0);
        $equipe2 = array('set' => 0, 'jeu' => 0, 'point' => 0);
        $score = array(
            'set' => array(),
            'jeu' => array(),
            'point' => array()
        );
        /** @var Point $point */
        foreach ($match->getPoints() as $point)
        {
            if ($match->getEquipes1() == $point->getEquipe()) {
                $equipe1['point'] += 1;
            } else {
                $equipe2['point'] += 1;
            }
            if ($this->leJeuEstTermine($equipe1, $equipe2)) {
                $equipe1['point'] = 0;
                $equipe2['point'] = 0;
                if ($match->getEquipes1() == $point->getEquipe()) {
                    $equipe1['jeu'] += 1;
                } else {
                    $equipe2['jeu'] += 1;
                }
                if ($this->leSetEstTermine($equipe1, $equipe2)) {
                    $score['jeu'] = array('equipe1' => $equipe1['jeu'], 'equipe2' => $equipe2['jeu']);
                    $equipe1['jeu'] = 0;
                    $equipe2['jeu'] = 0;
                    if ($match->getEquipes1() == $point->getEquipe()) {
                        $equipe1['set'] += 1;
                    } else {
                        $equipe2['set'] += 1;
                    }
                    $score['set'] += array('equipe1' => $equipe1['jeu'], 'equipe2' => $equipe2['jeu']);$score['point'] = array('equipe1' => $equipe1['point'], 'equipe2' => $equipe2['point']);
                } else {
                    $score['jeu'] = array('equipe1' => $equipe1['jeu'], 'equipe2' => $equipe2['jeu']);
                    $score['point'] = array('equipe1' => $equipe1['point'], 'equipe2' => $equipe2['point']);
                }
            } else {
                $score['point'] = array('equipe1' => $equipe1['point'], 'equipe2' => $equipe2['point']);
            }
        }

        return $score;
    }

    public function leJeuEstTermine($equipe1, $equipe2) {

        $jeuTermine = false;
        if ($equipe1['point'] > 3 || $equipe2['point'] > 3) {
            // Si l'écart de point du jeu est supérieur à égal à 2
            if ($equipe1['point'] - $equipe2['point'] > 1 || $equipe2['point'] - $equipe1['point'] > 1) {
                $jeuTermine = true;
            }
        }

        return $jeuTermine;
    }

    public function leSetEstTermine($equipe1, $equipe2) {

        $setTermine = false;
        if ($equipe1['jeu'] > 5 || $equipe2['jeu'] > 5) {
            if ($equipe1['jeu'] - $equipe2['jeu'] > 1 || $equipe2['jeu'] - $equipe1['jeu'] > 1) {
                $setTermine = false;
            }
        }

        return $setTermine;
    }
}