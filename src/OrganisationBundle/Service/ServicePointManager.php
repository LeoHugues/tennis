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
    private $premierTour = true;
    /** @var  Point */
    private $ancienPoint;

    private $debutPose;
    private $finPose;

    private $tieBreak = false;
    private $service;

    public function __construct($em)
    {
        $this->em = $em;
    }

    public function initFinPause(){
        $this->finPose = new \DateTime();
    }

    public function estFinJeu($match)
    {
        $equipe1    = array('set' => 0, 'jeu' => 0, 'point' => 0);
        $equipe2    = array('set' => 0, 'jeu' => 0, 'point' => 0);
        $resultat = false;

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
                    $score['set'][] = array('equipe1' => $equipe1['jeu'], 'equipe2' => $equipe2['jeu']);
                    $equipe1['jeu'] = 0;
                    $equipe2['jeu'] = 0;
                    if ($match->getEquipes1() == $point->getEquipe()) {
                        $equipe1['set'] += 1;
                    } else {
                        $equipe2['set'] += 1;
                    }
                }
                $resultat = true;
            }else{
                $resultat = false;
            }
        }
        return $resultat;
    }

    public function estPremierPointJeu($match){
        $equipe1    = array('set' => 0, 'jeu' => 0, 'point' => 0);
        $equipe2    = array('set' => 0, 'jeu' => 0, 'point' => 0);
        $premierPoint = false;
        $nbPoint    = 0;
        $resultat = false;
        /** @var Point $point */
        foreach ($match->getPoints() as $point)
        {
            if ($match->getEquipes1() == $point->getEquipe()) {
                $equipe1['point'] += 1;
            } else {
                $equipe2['point'] += 1;
            }
            $nbPoint++;
            if($nbPoint == 1 && $premierPoint == true){
                $resultat = true;
            }else{
                $resultat = false;
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
                    $score['set'][] = array('equipe1' => $equipe1['jeu'], 'equipe2' => $equipe2['jeu']);
                    $equipe1['jeu'] = 0;
                    $equipe2['jeu'] = 0;
                    if ($match->getEquipes1() == $point->getEquipe()) {
                        $equipe1['set'] += 1;
                    } else {
                        $equipe2['set'] += 1;
                    }
                }
                $nbPoint = 0;
                $premierPoint = true;
            }else{
                $premierPoint = false;
            }
        }
        return $resultat;
    }


    public function addPoint($idRencontre, $idEquipe) {
        /** @var Matchs $rencontre */
        $rencontre = $this->em->getRepository('OrganisationBundle:Matchs')->find($idRencontre);
        $point = new Point();
        if($this->premierTour == true){
            $this->premierTour = false;
            $point->setDatetimeDeb(new \Datetime());
        }elseif($this->estPremierPointJeu($rencontre) == false && $this->estFinJeu($rencontre) == false){
            $point->setDatetimeDeb($this->ancienPoint->getDatetimeFin());
        }elseif($this->estPremierPointJeu($rencontre) == true){
            $point->setDatetimeDeb($this->finPose);
        }elseif($this->estFinJeu($rencontre) == true){
            $this->debutPose = new \Datetime();
        }
        /** @var Equipe $equipe */
        $equipe = $this->em->getRepository('OrganisationBundle:Equipe')->find($idEquipe);
        $point->setDatetimeFin(new \DateTime());
        $point->setEquipe($equipe);
        $point->setMatch($rencontre);
        $this->em->persist($point);

        $rencontre->addPoint($point);
        $this->ancienPoint = $point;
        $this->em->persist($rencontre);
        $this->em->flush();

        return $this->getScore($rencontre);
    }

    public function getScore(Matchs $match)
    {
        $equipe1    = array('set' => 0, 'jeu' => 0, 'point' => 0);
        $equipe2    = array('set' => 0, 'jeu' => 0, 'point' => 0);
        $score      = array(
            'set'       => array(),
            'jeu'       => array(),
            'point'     => array(),
            'termine'   => false,
            'service'   => null,
            'finJeu'    => false
        );

        /** @var Point $point */
        foreach ($match->getPoints() as $point)
        {
            $score['finJeu'] = false;
            if ($match->getEquipes1() == $point->getEquipe()) {
                $equipe1['point'] += 1;
            } else {
                $equipe2['point'] += 1;
            }
            if ($this->leJeuEstTermine($equipe1, $equipe2)) {
                $score['finJeu'] = true;
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

                    $score['termine'] = $this->leMatchEstTermine($match, array('equipe1' => $equipe1['set'], 'equipe2' => $equipe2['set']));

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

        $score['service'] = $this->getServeur($match, array('equipe1' => $equipe1, 'equipe2' => $equipe2));

        return $score;
    }
    
    public function getServeur(Matchs $matchs, $score) {
        $service = 1;
        if ($matchs->getServicePremier() == $matchs->getEquipes1()->getId()) {
            $nbSet = $score['equipe1']['set'] + $score['equipe2']['set'];
            if ($nbSet % 2 == 1) { // L'equipe 2 sert en premier dans le set en cour
                $nbjeu = $score['equipe1']['jeu'] + $score['equipe2']['jeu'];

                if ($nbjeu == 12) { // Tie break (L'equipe 2 commence par servir)
                    $nbPoint = $score['equipe1']['point'] + $score['equipe2']['point'];
                    if ($nbPoint == 0 ) {
                        $service = 2;
                    } else {
                        $nbChangementService = round($nbPoint / 2, 0, PHP_ROUND_HALF_UP);
                        if ($nbChangementService % 2 == 0) {
                            $service = 2;
                        }
                    }
                } elseif ($nbjeu % 2 == 0) {
                    $service = 2;
                }
            } else { // L'equipe 1 sert en premier
                $nbjeu = $score['equipe1']['jeu'] + $score['equipe2']['jeu'];

                if ($nbjeu == 12) { // Tie break (L'equipe 1 commence par servir)
                    $nbPoint = $score['equipe1']['point'] + $score['equipe2']['point'];
                    if ($nbPoint > 0 ) {
                        $nbChangementService = round($nbPoint / 2, 0, PHP_ROUND_HALF_UP);
                        if ($nbChangementService % 2 == 1) {
                            $service = 2;
                        }
                    }
                } elseif ($nbjeu % 2 == 0) {
                    $service = 2;
                }
            }
        } else {
            $nbSet = $score['equipe1']['set'] + $score['equipe2']['set'];
            if ($nbSet % 2 == 0) { // L'equipe 2 sert en premier dans le set en cour
                $nbjeu = $score['equipe1']['jeu'] + $score['equipe2']['jeu'];

                if ($nbjeu == 12) { // Tie break (L'equipe 2 commence par servir)
                    $nbPoint = $score['equipe1']['point'] + $score['equipe2']['point'];
                    if ($nbPoint == 0 ) {
                        $service = 2;
                    } else {
                        $nbChangementService = round($nbPoint / 2, 0, PHP_ROUND_HALF_UP);
                        if ($nbChangementService % 2 == 0) {
                            $service = 2;
                        }
                    }
                } elseif ($nbjeu % 2 == 0) {
                    $service = 2;
                }
            } else { // l'equipe1 sert en premier
                $nbjeu = $score['equipe1']['jeu'] + $score['equipe2']['jeu'];

                if ($nbjeu == 12) { // Tie break (L'equipe 1 commence par servir)
                    $nbPoint = $score['equipe1']['point'] + $score['equipe2']['point'];
                    if ($nbPoint > 0 ) {
                        $nbChangementService = round($nbPoint / 2, 0, PHP_ROUND_HALF_UP);
                        if ($nbChangementService % 2 == 1) {
                            $service = 2;
                        }
                    }
                } elseif ($nbjeu % 2 == 0) {
                    $service = 2;
                }
            }
        }

        return $service;
    }

    public function leJeuEstTermine($equipe1, $equipe2){

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
        $resultat = false;
        if($matchs->getNbSets() == 5) {
            if ($score['equipe1'] == 3) {
                $resultat = true;
            } elseif ($score['equipe2'] == 3) {
                $resultat = true;
            }
        }elseif($matchs->getNbSets() == 3) {
            if($score['equipe1'] == 2) {
                $resultat = true;
            }elseif($score['equipe2'] == 2) {
                $resultat = true;
            }
        }
            return $resultat;

    }
}