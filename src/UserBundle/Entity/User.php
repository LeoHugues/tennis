<?php

namespace UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use OrganisationBundle\Entity\Joueur;
use OrganisationBundle\Entity\Matchs;

/**
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Matchs
     *
     * @ORM\OneToMany(targetEntity="OrganisationBundle\Entity\Matchs", mappedBy="arbitre", cascade={"persist", "remove"})
     */
    private $matchs;

    /**
     * @ORM\OneToMany(targetEntity="OrganisationBundle\Entity\Joueur", mappedBy="user")
     */
    private $joueurs;

    public function __construct()
    {
        parent::__construct();
        $this->joueurs = new ArrayCollection();
    }

    public function getRoleString(){
        $roles = parent::getRoles();
        if(!empty($roles)){
            return $roles[0];
        }
        else return "";
    }

    public function setRoleString($role){
        $this->addRole($role);
    }

    /**
     * @return Matchs
     */
    public function getMatchs()
    {
        return $this->matchs;
    }

    /**
     * @param Matchs $matchs
     */
    public function setMatchs($matchs)
    {
        $this->matchs = $matchs;
    }

    public function addJoueur(Joueur $joueur)
    {
        $this->joueurs[] = $joueur;

        $joueur->setUser($this);

        return $this;
    }

    public function removeJoueur(Joueur $joueur)
    {
        $this->joueurs->removeElement($joueur);
    }

    public function getJoueurs()
    {
        return $this->joueurs;
    }
}