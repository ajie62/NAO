<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityRepository;

class ObservationRepository extends EntityRepository
{
    /**
     * @return array
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findNumberOfObservationsAndStats()
    {
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
    }

    /**
     * @param User $user
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findNumberOfAwaitingObservations(User $user){
        return $this->createQueryBuilder('observation')
            ->select('COUNT(observation)')
            ->andWhere('observation.validated = :valid')
            ->setParameter('valid', false)
            ->andWhere('observation.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param $number
     * @param $total
     * @return float|int|void
     */
    private function pourcentage($number, $total) {
        if (!$number)
            return;

        return $number * 100 / $total;
    }
}
