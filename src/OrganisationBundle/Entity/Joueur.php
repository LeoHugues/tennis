<?php

namespace OrganisationBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Joueur
 *
 * @ORM\Table(name="joueur")
 * @ORM\Entity(repositoryClass="OrganisationBundle\Repository\JoueurRepository")
 */
class Joueur
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
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $prenom;

    /**
     * @var int
     *
     * @ORM\Column(name="nb_victoire", type="integer", nullable=true)
     */
    private $nbVictoire;

    /**
     * @var datetime
     *
     * @ORM\Column(name="date_de_naissance", type="datetime", nullable=true)
     */
    private $naissance;

    /**
     * @var pays
     *
     * @ORM\ManyToOne(targetEntity="OrganisationBundle\Entity\Pays")
     * @ORM\JoinColumn(name="nationalite", referencedColumnName="code")
     */
    private $nationalite;

    /**
     * @var int
     *
     * @ORM\Column(name="classement", type="integer", nullable=false)
     */
    private $classement;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="joueurs")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="OrganisationBundle\Entity\Avertissement", mappedBy="joueur")
     */
    private $avertissements;


    public function __construct() {
        $this->nbVictoire      = 0;
        $this->avertissements  = new ArrayCollection();
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->getNom() . " - " . $this->getPrenom();
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
     * @return Joueur
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
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Joueur
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set nbVictoire
     *
     * @param integer $nbVictoire
     *
     * @return Joueur
     */
    public function setNbVictoire($nbVictoire)
    {
        $this->nbVictoire = $nbVictoire;

        return $this;
    }

    /**
     * Get nbVictoire
     *
     * @return int
     */
    public function getNbVictoire()
    {
        return $this->nbVictoire;
    }

    /**
     * Set naissance
     *
     * @param datetime $naissance
     *
     * @return Joueur
     */
    public function setNaissance($naissance)
    {
        $this->naissance = $naissance;

        return $this;
    }

    /**
     * Get naissance
     *
     * @return datetime
     */
    public function getNaissance()
    {
        return $this->naissance;
    }

    /**
     * Set nationnalite
     *
     * @param string $nationalite
     *
     * @return Joueur
     */
    public function setNationalite($nationalite)
    {
        $this->nationalite = $nationalite;

        return $this;
    }

    /**
     * Get nationalite
     *
     * @return string
     */
    public function getNationalite()
    {
        return $this->nationalite;
    }

    /**
     * Set classement
     *
     * @param int classement
     *
     * @return Joueur
     */
    public function setClassement($classement)
    {
        $this->classement = $classement;

        return $this;
    }

    /**
     * Get classement
     *
     * @return int
     */
    public function getClassement()
    {
        return $this->classement;
    }

    public function addAvertissement(Avertissement $avertissement)
    {
        $this->avertissements[] = $avertissement;

        return $this;
    }

    public function removeAvertissement(Avertissement $avertissement)
    {
        $this->avertissements->removeElement($avertissement);
    }

    public function getAvertissements()
    {
        return $this->avertissements;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }
}

