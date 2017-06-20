<?php
/**
 * Created by PhpStorm.
 * User: pierrebaumes
 * Date: 20/06/2017
 * Time: 16:27
 */

namespace OrganisationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Advertissement
 *
 * @ORM\Table(name="advertissement")
 * @ORM\Entity(repositoryClass="OrganisationBundle\Repository\AvertissementRepository")
 */
class Avertissement
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
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
     * @ORM\ManyToOne(targetEntity="Organisationbundle\Entity\Matchs", inversedBy="advertissements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $match;

    /**
     * @ORM\ManyToOne(targetEntity="Organisationbundle\Entity\Joueur", inversedBy="advertissements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $joueur;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getMotif()
    {
        return $this->motif;
    }

    /**
     * @param string $motif
     */
    public function setMotif($motif)
    {
        $this->motif = $motif;
    }

    /**
     * @return mixed
     */
    public function getMatch()
    {
        return $this->match;
    }

    /**
     * @param mixed $match
     */
    public function setMatch($match)
    {
        $this->match = $match;
    }

    /**
     * @return mixed
     */
    public function getJoueur()
    {
        return $this->joueur;
    }

    /**
     * @param mixed $joueur
     */
    public function setJoueur($joueur)
    {
        $this->joueur = $joueur;
    }
}