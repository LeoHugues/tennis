<?php

namespace TennisBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Equipe
 *
 * @ORM\Table(name="equipe")
 * @ORM\Entity(repositoryClass="TennisBundle\Repository\EquipeRepository")
 */
class Equipe
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
     * @var \stdClass
     *
     * @ORM\Column(name="joueur1", type="object")
     * @ORM\ManyToOne(targetEntity="TennisBundle\Entity\Joueur")
     * @ORM\JoinColumn(nullable=false)
     */
    private $joueur1;

    /**
     * @var \stdClass
     *
     * @ORM\Column(name="joueur2", type="object", nullable=true)
     * @ORM\ManyToOne(targetEntity="TennisBundle\Entity\Joueur")
     * @ORM\JoinColumn(nullable=false)
     */
    private $joueur2;


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
     * Set joueur1
     *
     * @param \stdClass $joueur1
     *
     * @return Equipe
     */
    public function setJoueur1($joueur1)
    {
        $this->joueur1 = $joueur1;

        return $this;
    }

    /**
     * Get joueur1
     *
     * @return \stdClass
     */
    public function getJoueur1()
    {
        return $this->joueur1;
    }

    /**
     * Set joueur2
     *
     * @param \stdClass $joueur2
     *
     * @return Equipe
     */
    public function setJoueur2($joueur2)
    {
        $this->joueur2 = $joueur2;

        return $this;
    }

    /**
     * Get joueur2
     *
     * @return \stdClass
     */
    public function getJoueur2()
    {
        return $this->joueur2;
    }
}

