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

    public function getScore($match)
    {
        //$match = $this->em->getRepository('OrganisationBundle:Matchs')->find($idRencontre);
        $equipe1 = array('set' => 0, 'jeu' => 0, 'point' => 0);
        $equipe2 = array('set' => 0, 'jeu' => 0, 'point' => 0);
        $score = array(
            'set' => array(),
            'jeu' => array(
                'equipe1' => 0,
                'equipe2' => 0
            ),
            'point' => array(
                'equipe1' => 0,
                'equipe2' => 0
            )
        );

        /** @var Point $point */
        foreach ($match->getPoints() as $point)
        {
            if ($equipe1['point'] > 3 || $equipe2['point'] > 3) {
                $jeuTermine = false;
                if ($equipe1['point'] - $equipe2['point']  > 1 ) {
                    $equipe1['jeu'] += 1;
                    $jeuTermine = true;
                } elseif ($equipe2['point'] - $equipe1['point']  > 1 ){
                    $equipe2['jeu'] += 1;
                    $jeuTermine = true;
                }

                if ($jeuTermine == true) {
                    $equipe1['point'] = 0;
                    $equipe2['point'] = 0;

                    if ($equipe1['jeu'] > 5 || $equipe2['jeu'] > 5) {
                        $setTermine = false;
                        if ($equipe1['jeu'] - $equipe2['jeu']  > 1 ) {
                            $equipe1['set'] += 1;
                            $setTermine = true;
                        } elseif ($equipe2['jeu'] - $equipe1['jeu']  > 1 ){
                            $equipe2['set'] += 1;
                            $setTermine = true;
                        }

                        if ($setTermine == true) {
                            $score['set'] += array(array('equipe1' => $equipe1['jeu'], 'equipe2' => $equipe2['jeu']));
                            $equipe1['jeu'] = 0;
                            $equipe2['jeu'] = 0;
                        }
                    }
                } else {
                    if ($match->getEquipes1() == $point->getEquipe()) {
                        $equipe1['jeu'] += 1;
                    } else {
                        $equipe2['jeu'] += 1;
                    }
                    $score['jeu'] += array('equipe1' => array($equipe1['jeu'], 'equipe2' => $equipe2['jeu']));
                }
            } else {
                if ($match->getEquipes1() == $point->getEquipe()) {
                    $equipe1['point'] += 1;
                } else {
                    $equipe2['point'] += 1;
                }
                $score['point'] += array('equipe1' => array($equipe1['point'], 'equipe2' => $equipe2['point']));
            }
        }

        return $score;
    }
}