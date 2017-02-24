<?php

namespace OrganisationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Incident
 *
 * @ORM\Table(name="incident")
 * @ORM\Entity(repositoryClass="OrganisationBundle\Repository\IncidentRepository")
 */
class Incident
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
     * @return Incident
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
     * Set datetimeDeb
     *
     * @param \DateTime $datetimeDeb
     *
     * @return Incident
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
     * @return Incident
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
}

