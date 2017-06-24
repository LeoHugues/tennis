<?php
/**
 * Created by PhpStorm.
 * User: julien_mathe
 * Date: 21/06/2017
 * Time: 11:19
 */
namespace OrganisationBundle\Service;
use OrganisationBundle\Entity\Matchs;
use Doctrine\ORM\EntityManager;

class MatchStatManager{

    const BALLE_SET_E1 = 1;
    const BALLE_BREAK_E1 = 2;
    const BALLE_SET_E2 = 3;
    const BALLE_BREAK_E2 = 4;
    const BALLE_MATCH_E1 = 5;
    const BALLE_MATCH_E2 = 6;
    const JEU_BLANC_E1 = 7;
    const JEU_BLANC_E2 = 8;

    private $em;
    private $pointManager;

    public function __construct($em, $pm){
        $this->em = $em;
        $this->pointManager = $pm;
    }

    public function getStats(Matchs $match)
    {
        $equipe1 = array('set' => 0, 'jeu' => 0, 'point' => 0, 'balleSet' => 0, 'balleBreak' => 0, 'balleMatch' => 0, 'jeuBlanc' => 0);
        $equipe2 = array('set' => 0, 'jeu' => 0, 'point' => 0, 'balleSet' => 0, 'balleBreak' => 0, 'balleMatch' => 0, 'jeuBlanc' => 0);
        foreach ($match->getPoints() as $point) {
            if($this->estBalleSet($match, $equipe1, $equipe2, array('equipe1' => $equipe1, 'equipe2' => $equipe2)) == $this::BALLE_SET_E1){
                $equipe1['balleSet'] += 1;
            }elseif($this->estBalleSet($match, $equipe1, $equipe2, array('equipe1' => $equipe1, 'equipe2' => $equipe2)) == $this::BALLE_SET_E2){
                $equipe2['balleSet'] += 1;
            }
            if($this->estBalleBreak($match, $equipe1, $equipe2, array('equipe1' => $equipe1, 'equipe2' => $equipe2)) == $this::BALLE_BREAK_E1){
                $equipe1['balleBreak'] += 1;
            }elseif($this->estBalleBreak($match, $equipe1, $equipe2, array('equipe1' => $equipe1, 'equipe2' => $equipe2)) == $this::BALLE_BREAK_E2){
                $equipe2['balleBreak'] += 1;
            }
            if($match->getNbSets() == 3){
                if($this->estBalleMatch3Set($equipe1, $equipe2) == $this::BALLE_MATCH_E1){
                    $equipe1['balleMatch'] += 1;
                }elseif($this->estBalleMatch3Set($equipe1, $equipe2) == $this::BALLE_MATCH_E2){
                    $equipe2['balleMatch'] += 1;
                }
            }elseif($match->getNbSets() == 5){
                if($this->estBalleMatch5Set($equipe1, $equipe2) == $this::BALLE_MATCH_E1){
                    $equipe1['balleMatch'] += 1;
                }elseif($this->estBalleMatch5Set($equipe1, $equipe2) == $this::BALLE_MATCH_E2){
                    $equipe2['balleMatch'] += 1;
                }
            }
            if ($match->getEquipes1() == $point->getEquipe()) {
                $equipe1['point'] += 1;
            } else {
                $equipe2['point'] += 1;
            }
            if ($this->pointManager->leJeuEstTermine($equipe1, $equipe2)) {
                if($this->estJeuBlanc($equipe1, $equipe2) == $this::JEU_BLANC_E1){
                    $equipe1['jeuBlanc'] += 1;
                }elseif($this->estJeuBlanc($equipe1, $equipe2) == $this::JEU_BLANC_E2){
                    $equipe2['jeuBlanc'] += 1;
                }
                $equipe1['point'] = 0;
                $equipe2['point'] = 0;
                if ($match->getEquipes1() == $point->getEquipe()) {
                    $equipe1['jeu'] += 1;
                } else {
                    $equipe2['jeu'] += 1;
                }
                if ($this->pointManager->leSetEstTermine($equipe1, $equipe2)) {
                    $score['set'][] = array('equipe1' => $equipe1['jeu'], 'equipe2' => $equipe2['jeu']);
                    $equipe1['jeu'] = 0;
                    $equipe2['jeu'] = 0;
                    if ($match->getEquipes1() == $point->getEquipe()) {
                        $equipe1['set'] += 1;
                    } else {
                        $equipe2['set'] += 1;
                    }

                    $score['termine'] = $this->pointManager->leMatchEstTermine($match, array('equipe1' => $equipe1['set'], 'equipe2' => $equipe2['set']));

                    $score['jeu']   = array('equipe1' => $equipe1['jeu'], 'equipe2' => $equipe2['jeu']);
                    $score['point'] = $this->pointManager->parsePoint(array('equipe1' => $equipe1['point'], 'equipe2' => $equipe2['point']));
                } else {

                    $score['jeu']   = array('equipe1' => $equipe1['jeu'], 'equipe2' => $equipe2['jeu']);
                    $score['point'] = $this->pointManager->parsePoint(array('equipe1' => $equipe1['point'], 'equipe2' => $equipe2['point']));
                }
            } else {
                $score['point'] = $this->pointManager->parsePoint(array('equipe1' => $equipe1['point'], 'equipe2' => $equipe2['point']));
            }
        }

        $score['service'] = $this->pointManager->getServeur($match, array('equipe1' => $equipe1, 'equipe2' => $equipe2));
        return array($equipe1['balleSet'], $equipe1['balleBreak'], $equipe1['balleMatch'], $equipe1['jeuBlanc'], $equipe2['balleSet'], $equipe2['balleBreak'], $equipe2['balleMatch'], $equipe2['jeuBlanc']);
    }


    public function estBalleSet($match, $equipe1, $equipe2, $score){
        //Equipe 1
        //Balle de set pour equipe 1 en 6 jeu
        if($equipe1['jeu'] == 5 && $equipe2['jeu'] <= 4){
            //Balle de jeu sans avantage
            if($equipe1['point'] == 3 && $equipe2['point'] <= 2){
                return $this::BALLE_SET_E1;
            }
            //Balle de jeu avec avantage
            if($equipe1['point'] > 3 && ($equipe1['point'] - $equipe2['point']) == 1){
                return $this::BALLE_SET_E1;

            }
        }
        //Balle de set pour equipe 1 en 7 jeu
        elseif($equipe1['jeu'] == 6 && $equipe2['jeu'] = 5){
            //Balle de jeu sans avantage
            if($equipe1['point'] == 3 && $equipe2['point'] <= 2){
                return $this::BALLE_SET_E1;

            }
            //Balle de jeu avec avantage
            if($equipe1['point'] > 3 && ($equipe1['point'] - $equipe2['point']) == 1){
                return $this::BALLE_SET_E1;

            }
        }
        //Balle de set pour equipe 1 avec tie break
        elseif($equipe1['jeu'] == 6 && $equipe2['jeu'] == 6){
            if($equipe1['point'] >= 6 && ($equipe1['point'] - $equipe2['point']) == 1){
                return $this::BALLE_SET_E1;

            }
        }
        //Equipe 2
        //Balle de set pour equipe 2 en 6 jeu
        elseif($equipe2['jeu'] == 5 && $equipe1['jeu'] <= 4){
            //Balle de jeu sans avantage
            if($equipe2['point'] == 3 && $equipe1['point'] <= 2){
                return $this::BALLE_SET_E2;

            }
            //Balle de jeu avec avantage
            if($equipe2['point'] > 3 && ($equipe2['point'] - $equipe1['point']) == 1){
                return $this::BALLE_SET_E2;

            }
        }
        //Balle de set pour equipe 2 en 7 jeu
        elseif($equipe2['jeu'] == 6 && $equipe1['jeu'] == 5){
            //Balle de jeu sans avantage
            if($equipe2['point'] == 3 && $equipe1['point'] <= 2){
                return $this::BALLE_SET_E2;

            }
            //Balle de jeu avec avantage
            if($equipe2['point'] > 3 && ($equipe2['point'] - $equipe1['point']) == 1){
                return $this::BALLE_SET_E2;

            }
        }
        //Balle de set pour equipe 2 avec tie break
        elseif($equipe2['jeu'] == 6 && $equipe1['jeu'] == 6){
            if($equipe2['point'] >= 6 && ($equipe2['point'] - $equipe1['point']) == 1){
                return $this::BALLE_SET_E2;

            }
        }else{
            return 0;
        }

    }

    public function estBalleBreak($match, $equipe1, $equipe2, $score){

        //Equipe1
        if($equipe1['point'] == 3 && $equipe2['point'] <= 2){
            if($this->pointManager->getServeur($match, $score) == 1){
                return $this::BALLE_BREAK_E1;
            }
        }
        //Balle de jeu avec avantage
        elseif($equipe1['point'] > 3 && ($equipe1['point'] - $equipe2['point']) == 1){
            if($this->pointManager->getServeur($match, $score) == 1){
                return $this::BALLE_BREAK_E1;
            }
        }elseif($equipe2['point'] == 3 && $equipe1['point'] <= 2){
            if($this->pointManager->getServeur($match, $score) == 2){
                return $this::BALLE_BREAK_E2;
            }
        }
        //Balle de jeu avec avantage
        elseif($equipe2['point'] > 3 && ($equipe2['point'] - $equipe1['point']) == 1){
            if($this->pointManager->getServeur($match, $score) == 2){
                return $this::BALLE_BREAK_E2;
            }
        }else{
            return 0;
        }
    }

    public function estBalleMatch3Set($equipe1, $equipe2){
        //Equipe1
        if(($equipe1['set'] == 1 && $equipe2['set'] <= 1)){
            //Balle de match en 6 jeu
            if($equipe1['jeu'] == 5 && $equipe2['jeu'] <= 4){
                //Balle de match sans avantage
                if ($equipe1['point'] == 3 && $equipe2['point'] <= 2) {
                    return $this::BALLE_MATCH_E1;
                }
                //Balle de jeu avec avantage
                elseif($equipe1['point'] > 3 && ($equipe1['point'] - $equipe2['point']) == 1){
                    return $this::BALLE_MATCH_E1;
                }
            }
            //Balle de match en 7 jeu
            elseif($equipe1['jeu'] == 6 && $equipe2['jeu'] == 5){
                //Balle de match sans avantage
                if ($equipe1['point'] == 3 && $equipe2['point'] <= 2) {
                    return $this::BALLE_MATCH_E1;
                }
                //Balle de jeu avec avantage
                elseif ($equipe1['point'] > 3 && ($equipe1['point'] - $equipe2['point']) == 1) {
                    return $this::BALLE_MATCH_E1;
                }
            }
            //Balle de match avec tie break
            elseif($equipe1['jeu'] == 6 && $equipe2['jeu'] == 6) {
                if ($equipe1['point'] >= 6 && ($equipe1['point'] - $equipe2['point']) == 1) {
                    return $this::BALLE_MATCH_E1;
                }
            }
        }
        //Equipe2
        elseif(($equipe2['set'] == 1 && $equipe1['set'] <= 1)){
            //Balle de match en 6 jeu
            if($equipe2['jeu'] == 5 && $equipe1['jeu'] <= 4){
                //Balle de match sans avantage
                if ($equipe2['point'] == 3 && $equipe1['point'] <= 2) {
                    return $this::BALLE_MATCH_E2;
                }
                //Balle de jeu avec avantage
                elseif($equipe2['point'] > 3 && ($equipe2['point'] - $equipe1['point']) == 1){
                    return $this::BALLE_MATCH_E2;
                }
            }
            //Balle de match en 7 jeu
            elseif($equipe2['jeu'] == 6 && $equipe1['jeu'] == 5){
                //Balle de match sans avantage
                if ($equipe2['point'] == 3 && $equipe1['point'] <= 2) {
                    return $this::BALLE_MATCH_E2;
                }
                //Balle de jeu avec avantage
                elseif ($equipe2['point'] > 3 && ($equipe2['point'] - $equipe1['point']) == 1) {
                    return $this::BALLE_MATCH_E2;
                }
            }
            //Balle de match avec tie break
            elseif($equipe2['jeu'] == 6 && $equipe1['jeu'] == 6) {
                if ($equipe2['point'] >= 6 && ($equipe2['point'] - $equipe1['point']) == 1) {
                    return $this::BALLE_MATCH_E2;
                }
            }
        }else{
            return 0;
        }
    }

    public function estBalleMatch5Set($equipe1, $equipe2){
        //Equipe1
        if(($equipe1['set'] == 2 && $equipe2['set'] <= 2)){
            //Balle de match en 6 jeu
            if($equipe1['jeu'] == 5 && $equipe2['jeu'] <= 4){
                //Balle de match sans avantage
                if ($equipe1['point'] == 3 && $equipe2['point'] <= 2) {
                    return $this::BALLE_MATCH_E1;
                }
                //Balle de jeu avec avantage
                elseif($equipe1['point'] > 3 && ($equipe1['point'] - $equipe2['point']) == 1){
                    return $this::BALLE_MATCH_E1;
                }
            }
            //Balle de match en 7 jeu
            elseif($equipe1['jeu'] == 6 && $equipe2['jeu'] == 5){
                //Balle de match sans avantage
                if ($equipe1['point'] == 3 && $equipe2['point'] <= 2) {
                    return $this::BALLE_MATCH_E1;
                }
                //Balle de jeu avec avantage
                elseif ($equipe1['point'] > 3 && ($equipe1['point'] - $equipe2['point']) == 1) {
                    return $this::BALLE_MATCH_E1;
                }
            }
            //Balle de match avec tie break
            elseif($equipe1['jeu'] == 6 && $equipe2['jeu'] == 6) {
                if ($equipe1['point'] >= 6 && ($equipe1['point'] - $equipe2['point']) == 1) {
                    return $this::BALLE_MATCH_E1;
                }
            }
        }
        //Equipe2
        elseif(($equipe2['set'] == 2 && $equipe1['set'] <= 2)){
            //Balle de match en 6 jeu
            if($equipe2['jeu'] == 5 && $equipe1['jeu'] <= 4){
                //Balle de match sans avantage
                if ($equipe2['point'] == 3 && $equipe1['point'] <= 2) {
                    return $this::BALLE_MATCH_E2;
                }
                //Balle de jeu avec avantage
                elseif($equipe2['point'] > 3 && ($equipe2['point'] - $equipe1['point']) == 1){
                    return $this::BALLE_MATCH_E2;
                }
            }
            //Balle de match en 7 jeu
            elseif($equipe2['jeu'] == 6 && $equipe1['jeu'] == 5){
                //Balle de match sans avantage
                if ($equipe2['point'] == 3 && $equipe1['point'] <= 2) {
                    return $this::BALLE_MATCH_E2;
                }
                //Balle de jeu avec avantage
                elseif ($equipe2['point'] > 3 && ($equipe2['point'] - $equipe1['point']) == 1) {
                    return $this::BALLE_MATCH_E2;
                }
            }
            //Balle de match avec tie break
            elseif($equipe2['jeu'] == 6 && $equipe1['jeu'] = 6) {
                if ($equipe2['point'] >= 6 && ($equipe2['point'] - $equipe1['point']) == 1) {
                    return $this::BALLE_MATCH_E2;
                }
            }
        }else{
            return 0;
        }
    }

    public function estJeuBlanc($equipe1, $equipe2){
        if($equipe1['point'] == 0){
            return $this::JEU_BLANC_E2;
        }elseif($equipe2['point'] == 0){
            return $this::JEU_BLANC_E1;
        }
    }



}