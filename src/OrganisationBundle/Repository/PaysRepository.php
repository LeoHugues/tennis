<?php

namespace OrganisationBundle\Repository;

/**
 * PaysRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PaysRepository extends \Doctrine\ORM\EntityRepository
{
    public function trouverTousPays(){
        $query = $this->_em->createQuery("SELECT p FROM OrganisationBundle:Pays p");
        return $query->getResult();
    }
}
