<?php

namespace TennisBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @var Terrain
     *
     * @ORM\ManyToOne(targetEntity="TennisBundle\Entity\Terrain", inversedBy="matchs", cascade={"persist", "remove"})
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
     * @var Avertissement
     *
     * @ORM\OneToMany(targetEntity="TennisBundle\Entity\Avertissement", mappedBy="matchs", cascade={"persist", "remove"})
     */
    private $avertissements;

    /**
     * @var Equipe
     *
     * @ORM\ManyToMany(targetEntity="TennisBundle\Entity\Equipe", cascade={"persist", "remove"})
     */
    private $equipes;


    /**
     * @var Point
     *
     * @ORM\OneToMany(targetEntity="TennisBundle\Entity\Point", mappedBy="match", cascade={"persist", "remove"})
     */
    private $points;

    /**
     *
     * @ORM\ManyToMany(targetEntity="TennisBundle\Entity\Incident")
     * @ORM\JoinTable(name="match_incidents",
     *      joinColumns={@ORM\JoinColumn(name="match_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="incident_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $incidents;

    /**
     * @var Arbitre
     *
     * @ORM\ManyToOne(targetEntity="TennisBundle\Entity\Arbitre", inversedBy="matchs", cascade={"persist", "remove"})
     */
    private $arbitre;

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

    /**
     * Add avertissement
     *
     * @param Avertissement $avert
     */
    public function addAvertissement(Avertissement $avert)
    {
        $avert->setMatchs($this);
        if (!$this->avertissements->contains($avert)) {
            $this->avertissements->add($avert);
        }
    }

    /**
     * Remove avertissement
     *
     * @param Avertissement $avert
     */
    public function removeAvertissement(Avertissement $avert)
    {
        $this->avertissements->removeElement($avert);
    }


    /**
     * Get avertissement
     *
     * @return ArrayCollection
     */
    public function getAvertissements()
    {
        return $this->avertissements;
    }

    /**
     * Add equipe
     *
     * @param Equipe $equipe
     */
    public function addEquipe(Equipe $equipe)
    {
        $equipe->addMatchs($this);
        if (!$this->equipes->contains($equipe)) {
            $this->equipes->add($equipe);
        }
    }

    /**
     * Remove equipe
     *
     * @param Equipe $equipe
     */
    public function removeEquipe(Equipe $equipe)
    {
        $this->equipes->removeElement($equipe);
    }


    /**
     * Get equipe
     *
     * @return ArrayCollection
     */
    public function getEquipes()
    {
        return $this->equipes;
    }

    /**
     * Add point
     *
     * @param Point $point
     */
    public function addPoint(Point $point)
    {
        $point->setMatch($this);
        if (!$this->points->contains($point)) {
            $this->points->add($point);
        }
    }

    /**
     * Remove point
     *
     * @param Point $point
     */
    public function removePoint(Point $point)
    {
        $this->points->removeElement($point);
    }


    /**
     * Get points
     *
     * @return ArrayCollection
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Add incident
     *
     * @param Incident $incident
     */
    public function addIncident(Incident $incident)
    {
        if (!$this->incidents->contains($incident)) {
            $this->incidents->add($incident);
        }
    }

    /**
     * Remove incident
     *
     * @param Incident $incident
     */
    public function removeIncident(Incident $incident)
    {
        $this->incidents->removeElement($incident);
    }


    /**
     * Get incidents
     *
     * @return ArrayCollection
     */
    public function getIncidents()
    {
        return $this->incidents;
    }

    /**
     * Set arbitre
     *
     * @param Arbitre $arbitre
     *
     * @return Matchs
     */
    public function setArbitre($arbitre)
    {
        $this->arbitre = $arbitre;

        return $this;
    }

    /**
     * Get arbitre
     *
     * @return Arbitre
     */
    public function getArbitre()
    {
        return $this->arbitre;
    }
}

