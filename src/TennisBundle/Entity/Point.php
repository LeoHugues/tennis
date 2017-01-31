<?php

namespace TennisBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Point
 *
 * @ORM\Table(name="point")
 * @ORM\Entity(repositoryClass="TennisBundle\Repository\PointRepository")
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
     * @ORM\Column(name="datetime_deb", type="datetime")
     */
    private $datetimeDeb;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datetime_fin", type="datetime")
     */
    private $datetimeFin;

    /**
     * @var \stdClass
     *
     * @ORM\Column(name="equipe", type="object")
     */
    private $equipe;

    /**
     * @var \stdClass
     *
     * @ORM\Column(name="matchs", type="object")
     */
    private $matchs;


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
     * Set matchs
     *
     * @param \stdClass $matchs
     *
     * @return Point
     */
    public function setMatchs($matchs)
    {
        $this->matchs = $matchs;

        return $this;
    }

    /**
     * Get matchs
     *
     * @return \stdClass
     */
    public function getMatchs()
    {
        return $this->matchs;
    }
}

