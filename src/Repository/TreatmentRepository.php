<?php

namespace App\Repository;

use App\Entity\Treatment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Treatment>
 *
 * @method Treatment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Treatment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Treatment[]    findAll()
 * @method Treatment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TreatmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Treatment::class);
    }
}
