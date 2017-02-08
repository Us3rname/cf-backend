<?php

namespace AppBundle\Repository;

use AppBundle\Entity\CrossfitUser;
use Doctrine\ORM\Tools\Pagination\Paginator;

class CrossfitUserRepository extends \Doctrine\ORM\EntityRepository
{

    public function getUsers($limit = 10, $page = 1): Paginator
    {

        $query = $this->createQueryBuilder('u')
            ->getQuery();

        $paginator = new Paginator($query);
        $paginator->getQuery()
            ->setFirstResult($limit * ($page - 1))// Offset
            ->setMaxResults($limit); // Limit

        return $paginator;
    }

    public function saveUser(CrossfitUser $user)
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }
}