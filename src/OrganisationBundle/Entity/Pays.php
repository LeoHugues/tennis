<?php
/**
 * Created by PhpStorm.
 * User: julien_mathe
 * Date: 07/06/2017
 * Time: 14:37
 */

namespace OrganisationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pays
 *
 * @ORM\Table(name="pays")
 * @ORM\Entity()
 */
class Pays
{
    /**
    * @var string
    * @ORM\Column(name="code", type="string", length=255, nullable=false)
    * @ORM\OneToMany(targetEntity="OrganisationBundle\Entity\Joueur")
    * @ORM\Id
    */
    private $code;


    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @ORM\Id
     */
//    private $name;


    /**
     * @var path
     *
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;

    public function __construct($code, $path) {
        $this->code = $code;
        $this->path = $path;
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->getCode();
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return Pays
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    public function getName(){
        return $this->name;
    }


}