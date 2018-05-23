<?php
/**
 * Created by PhpStorm.
 * User: jeromebutel
 * Date: 09/04/2018
 * Time: 14:51
 */

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class SpeciesRepository extends EntityRepository
{
    public function findWithData($data)
    {
        $qb = $this->createQueryBuilder('s')
            ->andWhere('s.name LIKE :name')
            ->setParameter('name', $data.'%')
            ->orderBy('s.name', 'ASC')
            ->getQuery()
        ;

        return $qb->execute();
    }
}