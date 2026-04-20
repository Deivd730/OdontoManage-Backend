<?php

namespace App\Repository;

use App\Entity\ToothTreatment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ToothTreatment>
 */
class ToothTreatmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ToothTreatment::class);
    }

    public function findByOdontogram(int $odontogramId)
    {
        return $this->createQueryBuilder('tt')
            ->andWhere('tt.odontogram = :odontogram_id')
            ->setParameter('odontogram_id', $odontogramId)
            ->orderBy('tt.toothNumber', 'ASC')
            ->addOrderBy('tt.toothFace', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByToothAndOdontogram(int $toothNumber, int $odontogramId)
    {
        return $this->createQueryBuilder('tt')
            ->andWhere('tt.toothNumber = :tooth_number')
            ->andWhere('tt.odontogram = :odontogram_id')
            ->setParameter('tooth_number', $toothNumber)
            ->setParameter('odontogram_id', $odontogramId)
            ->getQuery()
            ->getResult();
    }
}
