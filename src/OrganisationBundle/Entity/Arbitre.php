<?php

namespace OrganisationBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * Arbitre
 *
 * @ORM\Table(name="arbitre")
 * @ORM\Entity(repositoryClass="OrganisationBundle\Repository\ArbitreRepository")
 */
class Arbitre extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Matchs
     *
     * @ORM\OneToMany(targetEntity="OrganisationBundle\Entity\Matchs", mappedBy="arbitre", cascade={"persist", "remove"})
     */
    private $matchs;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    public function __toString()
    {
        return parent::__toString(); // TODO: Change the autogenerated stub
    }

    public function getId()
    {
        return parent::getId(); // TODO: Change the autogenerated stub
    }

    /**
     * Add match
     *
     * @param Matchs $match
     */
    public function addMatch(Matchs $match)
    {
        $match->setArbitre($this);
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
}

