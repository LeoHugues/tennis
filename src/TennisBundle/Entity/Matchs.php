<?php

namespace TennisBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Matchs
 *
 * @ORM\Table(name="matchs")
 * @ORM\Entity(repositoryClass="TennisBundle\Repository\MatchsRepository")
 */
class Matchs
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var \stdClass
     *
     * @ORM\Column(name="terrain", type="object")
     */
    private $terrain;

    /**
     * @var int
     *
     * @ORM\Column(name="nb_sets", type="integer")
     */
    private $nbSets;

    /**
     * @var string
     *
     * @ORM\Column(name="nvx_compet", type="string", length=255)
     */
    private $nvxCompet;

    /**
     * @var int
     *
     * @ORM\Column(name="service_premier", type="integer")
     */
    private $servicePremier;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Matchs
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set terrain
     *
     * @param \stdClass $terrain
     *
     * @return Matchs
     */
    public function setTerrain($terrain)
    {
        $this->terrain = $terrain;

        return $this;
    }

    /**
     * Get terrain
     *
     * @return \stdClass
     */
    public function getTerrain()
    {
        return $this->terrain;
    }

    /**
     * Set nbSets
     *
     * @param integer $nbSets
     *
     * @return Matchs
     */
    public function setNbSets($nbSets)
    {
        $this->nbSets = $nbSets;

        return $this;
    }

    /**
     * Get nbSets
     *
     * @return int
     */
    public function getNbSets()
    {
        return $this->nbSets;
    }

    /**
     * Set nvxCompet
     *
     * @param string $nvxCompet
     *
     * @return Matchs
     */
    public function setNvxCompet($nvxCompet)
    {
        $this->nvxCompet = $nvxCompet;

        return $this;
    }

    /**
     * Get nvxCompet
     *
     * @return string
     */
    public function getNvxCompet()
    {
        return $this->nvxCompet;
    }

    /**
     * Set servicePremier
     *
     * @param integer $servicePremier
     *
     * @return Matchs
     */
    public function setServicePremier($servicePremier)
    {
        $this->servicePremier = $servicePremier;

        return $this;
    }

    /**
     * Get servicePremier
     *
     * @return int
     */
    public function getServicePremier()
    {
        return $this->servicePremier;
    }
}

