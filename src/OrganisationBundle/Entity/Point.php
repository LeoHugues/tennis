<?php

namespace OrganisationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Point
 *
 * @ORM\Table(name="point")
 * @ORM\Entity(repositoryClass="OrganisationBundle\Repository\PointRepository")
 */
class Point
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
     * @ORM\Column(name="datetime_deb", type="datetime", nullable=true)
     */
    private $datetimeDeb;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datetime_fin", type="datetime", nullable=true)
     */
    private $datetimeFin;


    /**
     * @var Equipe
     *
     * @ORM\ManyToOne(targetEntity="OrganisationBundle\Entity\Equipe", inversedBy="points")
     */
    private $equipe;

    /**
     * @var Matchs
     *
     * @ORM\ManyToOne(targetEntity="OrganisationBundle\Entity\Matchs", inversedBy="points")
     */
    private $match;


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
     * Set datetimeDeb
     *
     * @param \DateTime $datetimeDeb
     *
     * @return Point
     */
    public function setDatetimeDeb($datetimeDeb)
    {
        $this->datetimeDeb = $datetimeDeb;

        return $this;
    }

    /**
     * Get datetimeDeb
     *
     * @return \DateTime
     */
    public function getDatetimeDeb()
    {
        return $this->datetimeDeb;
    }

    /**
     * Set datetimeFin
     *
     * @param \DateTime $datetimeFin
     *
     * @return Point
     */
    public function setDatetimeFin($datetimeFin)
    {
        $this->datetimeFin = $datetimeFin;

        return $this;
    }

    /**
     * Get datetimeFin
     *
     * @return \DateTime
     */
    public function getDatetimeFin()
    {
        return $this->datetimeFin;
    }

    /**
     * Set equipe
     *
     * @param \stdClass $equipe
     *
     * @return Point
     */
    public function setEquipe($equipe)
    {
        $this->equipe = $equipe;

        return $this;
    }

    /**
     * Get equipe
     *
     * @return \stdClass
     */
    public function getEquipe()
    {
        return $this->equipe;
    }

    /**
     * Set match
     *
     * @param \stdClass $match
     *
     * @return Point
     */
    public function setMatch($match)
    {
        $this->match = $match;

        return $this;
    }

    /**
     * Get match
     *
     * @return \stdClass
     */
    public function getMatch()
    {
        return $this->match;
    }
}

