<?php

namespace AppBundle\Repository;

use AppBundle\Paginator\Paginator;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
    public function getPaginatedList($filters, $order_by, $limit, $offset = 0) {

        $query = $this->getListQuery($filters, $order_by, $limit, $offset);

        $paginator = new Paginator($query, $fetchJoinCollection = false);
        return $paginator->getResult();
    }

    private function getListQuery(array $filters, array $order_by, int $limit, int $offset) {

        $em = $this->getEntityManager();

        $qb = $em->createQueryBuilder();
        $qb
            ->select('u')
            ->from('AppBundle:User','u');

        if(count($filters)) {

            $or = $qb->expr()->orx();

            foreach ($filters as $field => $filter) {

                $or->add($qb->expr()->eq('u.' . $field , ":$field"));
            }
            $qb->where($or);
            $qb->setParameters($filters);
        }

        $qb
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        return $qb;

    }
}