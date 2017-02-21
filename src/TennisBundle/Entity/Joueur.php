<?php

namespace TennisBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Joueur
 *
 * @ORM\Table(name="joueur")
 * @ORM\Entity(repositoryClass="TennisBundle\Repository\JoueurRepository")
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
     * @var Avertissement
     *
     * @ORM\OneToMany(targetEntity="TennisBundle\Entity\Avertissement", mappedBy="joueur",cascade={"persist", "remove"})
     */
    private $avertissements;


    public function __construct() {
        $this->nbVictoire = 0;
        $this->avertissements = new ArrayCollection();
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
     * Add avertissement
     *
     * @param Avertissement $avert
     */
    public function addAvertissement(Avertissement $avert)
    {
        $avert->setJoueur($this);
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
     * @return Avertissement
     */
    public function getAvertissements()
    {
        return $this->avertissements;
    }
}

