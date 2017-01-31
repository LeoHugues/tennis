<?php

namespace TennisBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Avertissement
 *
 * @ORM\Table(name="avertissement")
 * @ORM\Entity(repositoryClass="TennisBundle\Repository\AvertissementRepository")
 */
class Avertissement
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
     * @ORM\Column(name="motif", type="string", length=255)
     */
    private $motif;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datetime", type="datetime")
     */
    private $datetime;

    /**
     * @var Matchs
     *
     * @ORM\ManyToOne(targetEntity="TennisBundle\Entity\Matchs", cascade={"persist", "remove"})
     */
    private $match;

    /**
     * @var Joueur
     *
     * @ORM\ManyToOne(targetEntity="TennisBundle\Entity\Joueur", inversedBy="avertissements", cascade={"persist", "remove"})
     */
    private $joueur;

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
     * Set motif
     *
     * @param string $motif
     *
     * @return Avertissement
     */
    public function setMotif($motif)
    {
        $this->motif = $motif;

        return $this;
    }

    /**
     * Get motif
     *
     * @return string
     */
    public function getMotif()
    {
        return $this->motif;
    }

    /**
     * Set datetime
     *
     * @param \DateTime $datetime
     *
     * @return Avertissement
     */
    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * Get datetime
     *
     * @return \DateTime
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * Set matchs
     *
     * @param Matchs $match
     *
     * @return Avertissement
     */
    public function setMatch($match)
    {
        $this->match = $match;

        return $this;
    }

    /**
     * Get match
     *
     * @return Matchs
     */
    public function getMatch()
    {
        return $this->match;
    }

    /**
     * Set joueur
     *
     * @param Joueur $joueur
     *
     * @return Avertissement
     */
    public function setJoueur($joueur)
    {
        $this->joueur = $joueur;

        return $this;
    }

    /**
     * Get joueur
     *
     * @return \stdClass
     */
    public function getJoueur()
    {
        return $this->joueur;
    }
}

