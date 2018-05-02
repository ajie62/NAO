<?php
/**
 * Created by PhpStorm.
 * User: jeromebutel
 * Date: 16/04/2018
 * Time: 10:04
 */

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use function dump;

class UserRepository extends EntityRepository
{
    public function findStatsUser(){
        $total = $this->createQueryBuilder('user')
            ->select('COUNT(user)')
            ->andWhere('user.roles <> :admin')
            ->setParameter('admin', 'a:1:{i:0;s:10:"ROLE_ADMIN";}')
            ->getQuery()
            ->getSingleScalarResult();

        $particulier = $this->createQueryBuilder('user')
            ->select('COUNT(user)')
            ->AndWhere('user.roles = :role')
            ->setParameter('role', 'a:1:{i:0;s:16:"ROLE_PARTICULIER";}')
            ->getQuery()
            ->getSingleScalarResult();

        $naturaliste = $this->createQueryBuilder('user')
            ->select('COUNT(user)')
            ->AndWhere('user.roles = :role')
            ->setParameter('role', 'a:1:{i:0;s:16:"ROLE_NATURALISTE";}')
            ->getQuery()
            ->getSingleScalarResult();

        $naturalistePourcentage = $this->pourcentage($naturaliste, $total);

        return [
          'total' => $total,
          'particulier' => $particulier,
          'naturaliste' => $naturaliste,
          'naturalistePourcentage' => $naturalistePourcentage
        ];
    }

    public function findUsersOrderedDesc(){
        return $this->createQueryBuilder('user')
            ->select('user')
            ->andWhere('user.roles <> :admin')
            ->setParameter('admin', 'a:1:{i:0;s:10:"ROLE_ADMIN";}')
            ->orderBy('user.firstname','DESC')
            ->getQuery()
            ->execute();
    }

    private function pourcentage($nombre, $total) {
        if ($total == 0){
            return 0;
        } else {
            return $nombre * 100 / $total;
        }
    }
}