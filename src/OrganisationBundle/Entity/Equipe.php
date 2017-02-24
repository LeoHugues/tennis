<?php

namespace OrganisationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Equipe
 *
 * @ORM\Table(name="equipe")
 * @ORM\Entity(repositoryClass="OrganisationBundle\Repository\EquipeRepository")
 */
class Equipe
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
     * @var Joueur
     *
     * @ORM\ManyToOne(targetEntity="OrganisationBundle\Entity\Joueur", inversedBy="equipes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $joueur1;

    /**
     * @var Joueur
     *
     * @ORM\ManyToOne(targetEntity="OrganisationBundle\Entity\Joueur", inversedBy="equipes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $joueur2;

    /**
     * @var Matchs
     *
     * @ORM\ManyToMany(targetEntity="OrganisationBundle\Entity\Matchs")
     */
    private $matchs;

    /**
     * @var Point
     *
     * @ORM\OneToMany(targetEntity="OrganisationBundle\Entity\Point", mappedBy="equipe")
     */
    private $points;

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
     * Set joueur1
     *
     * @param \stdClass $joueur1
     *
     * @return Equipe
     */
    public function setJoueur1($joueur1)
    {
        $this->joueur1 = $joueur1;

        return $this;
    }

    /**
     * Get joueur1
     *
     * @return \stdClass
     */
    public function getJoueur1()
    {
        return $this->joueur1;
    }

    /**
     * Set joueur2
     *
     * @param \stdClass $joueur2
     *
     * @return Equipe
     */
    public function setJoueur2($joueur2)
    {
        $this->joueur2 = $joueur2;

        return $this;
    }

    /**
     * Get joueur2
     *
     * @return \stdClass
     */
    public function getJoueur2()
    {
        return $this->joueur2;
    }

    /**
     * Add match
     *
     * @param matchs $match
     */
    public function addMatch(Matchs $match)
    {
        $match->addEquipe($this);
        if (!$this->matchs->contains($match)) {
            $this->matchs->add($match);
        }
    }

    /**
     * Remove match
     *
     * @param Matchs $match
     */
    public function removeMatch(Matchs $match)
    {
        $this->matchs->removeElement($match);
    }


    /**
     * Get matchs
     *
     * @return ArrayCollection
     */
    public function getMatchs()
    {
        return $this->matchs;
    }

    /**
     * Add point
     *
     * @param Points $point
     */
    public function addPoint(Point $point)
    {
        $point->setEquipe($this);
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
}

