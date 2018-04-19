<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class ObservationRepository extends EntityRepository
{
    public function findNumberOfObservationsAndStats(){

        $total = $this->createQueryBuilder('observation')
        ->select('COUNT(observation)')
        ->getQuery()
        ->getSingleScalarResult();

        $awaiting = $this->createQueryBuilder('observation')
            ->select('COUNT(observation)')
            ->AndWhere('observation.validated = :valid')
            ->setParameter('valid', false)
            ->getQuery()
            ->getSingleScalarResult();

        $validated = $this->createQueryBuilder('observation')
            ->select('COUNT(observation)')
            ->AndWhere('observation.validated = :valid')
            ->setParameter('valid', true)
            ->getQuery()
            ->getSingleScalarResult();

        return [
          'total' => $total,
          'awaiting' => $awaiting,
          'validated' => $validated,
          'obsPourcentage' => $this->pourcentage($validated, $total)
        ];
//        $rejeted = ;

    }
    private function pourcentage($nombre, $total) {
        return $nombre * 100 / $total;
    }
}
