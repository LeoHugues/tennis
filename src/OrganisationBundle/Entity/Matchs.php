<?php

namespace OrganisationBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Matchs
 *
 * @ORM\Table(name="matchs")
 * @ORM\Entity(repositoryClass="OrganisationBundle\Repository\MatchsRepository")
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
     * @ORM\ManyToOne(targetEntity="OrganisationBundle\Entity\Terrain", inversedBy="matchs")
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
     * @ORM\Column(name="service_premier", type="integer", nullable=true)
     */
    private $servicePremier;

    /**
     * @var Avertissement
     *
     * @ORM\OneToMany(targetEntity="OrganisationBundle\Entity\Avertissement", mappedBy="match", cascade={"persist", "remove"})
     */
    private $avertissements;

    /**
     * One Match has One Equipe1.
     * @ORM\OneToOne(targetEntity="OrganisationBundle\Entity\Equipe", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="equipe1_id", referencedColumnName="id")
     * @Assert\Type(type="OrganisationBundle\Entity\Equipe")
     * @Assert\Valid()
     */
    private $equipes1;

    /**
     * One Match has One Equipe2.
     * @ORM\OneToOne(targetEntity="OrganisationBundle\Entity\Equipe", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="equipe2_id", referencedColumnName="id")
     * @Assert\Type(type="OrganisationBundle\Entity\Equipe")
     * @Assert\Valid()
     */
    private $equipes2;

    /**
     * @var Point
     *
     * @ORM\OneToMany(targetEntity="OrganisationBundle\Entity\Point", mappedBy="match")
     */
    private $points;

    /**
     *
     * @ORM\ManyToMany(targetEntity="OrganisationBundle\Entity\Incident")
     * @ORM\JoinTable(name="match_incidents",
     *      joinColumns={@ORM\JoinColumn(name="match_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="incident_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $incidents;

    /**
     * @var Arbitre
     *
     * @ORM\ManyToOne(targetEntity="OrganisationBundle\Entity\Arbitre", inversedBy="matchs", cascade={"persist", "remove"})
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
     * @return mixed
     */
    public function getEquipes1()
    {
        return $this->equipes1;
    }

    /**
     * @param mixed $equipes1
     */
    public function setEquipes1($equipes1)
    {
        $this->equipes1 = $equipes1;
    }

    /**
     * @return mixed
     */
    public function getEquipes2()
    {
        return $this->equipes2;
    }

    /**
     * @param mixed $equipes2
     */
    public function setEquipes2($equipes2)
    {
        $this->equipes2 = $equipes2;
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

