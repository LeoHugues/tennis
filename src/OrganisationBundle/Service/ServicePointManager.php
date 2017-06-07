<?php

namespace OrganisationBundle\Service;


use Doctrine\ORM\EntityManager;
use OrganisationBundle\Entity\Equipe;
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

    private $tieBreak = false;
    private $service;

    public function __construct($em)
    {
        $this->em = $em;
    }

    public function addPoint($idRencontre, $idEquipe) {
        /** @var Matchs $rencontre */
        $rencontre = $this->em->getRepository('OrganisationBundle:Matchs')->find($idRencontre);
        /** @var Equipe $equipe */
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
    {
        $equipe1 = array('set' => 0, 'jeu' => 0, 'point' => 0);
        $equipe2 = array('set' => 0, 'jeu' => 0, 'point' => 0);
        $score = array(
            'set'       => array(),
            'jeu'       => array(),
            'point'     => array(),
            'termine'   => false,
            'service'   => null
        );

        if ($match->getServicePremier() == $match->getEquipes1()) {
            $score['service'] = 1;
        } else {
            $score['service'] = 2;
        }

        /** @var Point $point */
        foreach ($match->getPoints() as $point)
        {
            if ($match->getEquipes1() == $point->getEquipe()) {
                $equipe1['point'] += 1;
            } else {
                $equipe2['point'] += 1;
            }
            if ($this->leJeuEstTermine($equipe1, $equipe2)) {
                if ($match->getServicePremier() == $match->getEquipes1()) {
                    $score['service'] = 1;
                } else {
                    $score['service'] = 2;
                }

                $equipe1['point'] = 0;
                $equipe2['point'] = 0;
                if ($match->getEquipes1() == $point->getEquipe()) {
                    $equipe1['jeu'] += 1;
                } else {
                    $equipe2['jeu'] += 1;
                }
                if ($this->leSetEstTermine($equipe1, $equipe2)) {
                    $score['set'][] = array('equipe1' => $equipe1['jeu'], 'equipe2' => $equipe2['jeu']);
                    $equipe1['jeu'] = 0;
                    $equipe2['jeu'] = 0;
                    if ($match->getEquipes1() == $point->getEquipe()) {
                        $equipe1['set'] += 1;
                    } else {
                        $equipe2['set'] += 1;
                    }

                    $score['termine'] = $this->leMatchEstTermine($match, array('equipe1' => $equipe1['set'], 'equipe2', $equipe2['set']));

                    $score['jeu']   = array('equipe1' => $equipe1['jeu'], 'equipe2' => $equipe2['jeu']);
                    $score['point'] = $this->parsePoint(array('equipe1' => $equipe1['point'], 'equipe2' => $equipe2['point']));
                } else {

                    $score['jeu']   = array('equipe1' => $equipe1['jeu'], 'equipe2' => $equipe2['jeu']);
                    $score['point'] = $this->parsePoint(array('equipe1' => $equipe1['point'], 'equipe2' => $equipe2['point']));
                }
            } else {
                $score['point'] = $this->parsePoint(array('equipe1' => $equipe1['point'], 'equipe2' => $equipe2['point']));
            }
        }

        return $score;
    }

    public function leJeuEstTermine($equipe1, $equipe2) {

        $jeuTermine = false;
        if ($this->tieBreak == false) {
            if ($equipe1['point'] > 3 || $equipe2['point'] > 3) {
                // Si l'écart de point du jeu est supérieur ou égal à 2
                if ($equipe1['point'] - $equipe2['point'] > 1 || $equipe2['point'] - $equipe1['point'] > 1) {
                    $jeuTermine = true;
                }
            }
        } else {
            if ($equipe1['point'] > 6 || $equipe2['point'] > 6) {
                // Si l'écart de point du jeu est supérieur ou égal à 2
                if ($equipe1['point'] - $equipe2['point'] > 1 || $equipe2['point'] - $equipe1['point'] > 1) {
                    $jeuTermine = true;
                }
            }
        }

        return $jeuTermine;
    }

    public function leSetEstTermine($equipe1, $equipe2) {

        $setTermine = false;
        if ($equipe1['jeu'] > 5 || $equipe2['jeu'] > 5) {
            // Si l'écart de jeu du set est supérieur ou égal à 2
            if ($equipe1['jeu'] - $equipe2['jeu'] > 1 || $equipe2['jeu'] - $equipe1['jeu'] > 1) {
                $setTermine = true;
            } elseif ($equipe1['jeu'] == 7 && $equipe2['jeu'] == 6) {
                $setTermine = true;
                $this->tieBreak = false;
            } elseif ($equipe1['jeu'] == 6 && $equipe2['jeu'] == 7) {
                $setTermine = true;
                $this->tieBreak = false;
            }
        }

        if ($equipe1['jeu'] == 6 && $equipe2['jeu'] == 6) {
            $this->tieBreak = true;
        }

        return $setTermine;
    }

    public function parsePoint($point) {
        $convertedPoint = array('equipe1' => 0, 'equipe2' => 0);

        if ($this->tieBreak == false) {
            if ($point['equipe1'] < 4 && $point['equipe2'] < 4) {
                if ($point['equipe1'] < 3) {
                    $convertedPoint['equipe1'] = $point['equipe1'] * 15;
                }

                if ($point['equipe2'] < 3) {
                    $convertedPoint['equipe2'] = $point['equipe2'] * 15;
                }

                if ($point['equipe1'] == 3) {
                    $convertedPoint['equipe1'] = 40;
                }

                if ($point['equipe2'] == 3) {
                    $convertedPoint['equipe2'] = 40;
                }
            } else {
                if ($point['equipe1'] == $point['equipe2']) {
                    $convertedPoint['equipe1'] = 40;
                    $convertedPoint['equipe2'] = 40;
                } elseif ($point['equipe1'] < $point['equipe2']) {
                    $convertedPoint['equipe1'] = 40;
                    $convertedPoint['equipe2'] = "AV";
                } elseif ($point['equipe1'] > $point['equipe2']) {
                    $convertedPoint['equipe1'] = "AV";
                    $convertedPoint['equipe2'] = 40;
                }
            }
        } else {
            return $point;
        }

        return $convertedPoint;
    }

    public function leMatchEstTermine(Matchs $matchs, $score) {
        if($matchs->getNbSets() == 5) {
            if ($score['equipe1']['set'] - $score['equipe2']['set'] > 3) {
                return true;
            } elseif ($score['equipe2']['set'] - $score['equipe1']['set'] > 3) {
                return true;
            } elseif ($score['equipe1'] + $score['equipe2'] = 5) {
                return true;
            }

            return false;
        }
    }
}