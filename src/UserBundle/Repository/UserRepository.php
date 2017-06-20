<?php

namespace UserBundle\Repository;

/**
 * Created by PhpStorm.
 * User: pierrebaumes
 * Date: 16/03/2017
 * Time: 11:41
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
    public function getUsersOrga($role)
    {
        $qb = $this->createQueryBuilder('u')
            ->andWhere("u.roles like :role")
            ->setParameter('role', '%"'.$role.'"%');

        $query   = $qb->getQuery();
        $results = $query->getResult();

        return $results;
    }
}