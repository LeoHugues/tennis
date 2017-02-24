<?php

namespace OrganisationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Terrain
 *
 * @ORM\Table(name="terrain")
 * @ORM\Entity(repositoryClass="OrganisationBundle\Repository\TerrainRepository")
 */
class Terrain
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
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var Matchs
     *
     * @ORM\OneToMany(targetEntity="OrganisationBundle\Entity\Matchs", mappedBy="terrain", cascade={"persist", "remove"})
     */
    private $matchs;


    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->getNom();
    }

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
     * Set nom
     *
     * @param string $nom
     *
     * @return Terrain
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Add match
     *
     * @param Matchs $match
     */
    public function addMatch(Matchs $match)
    {
        $match->setterrain($this);
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
}

