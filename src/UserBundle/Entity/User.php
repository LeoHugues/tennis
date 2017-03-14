<?php

namespace UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use OrganisationBundle\Entity\Matchs;

/**
 * @ORM\Entity
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

    public function __construct()
    {
        parent::__construct();
        // your own logic
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