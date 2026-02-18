<?php

namespace App\Repository;

use App\Entity\Tooth;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tooth>
 *
 * @method Tooth|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tooth|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tooth[]    findAll()
 * @method Tooth[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ToothRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tooth::class);
    }
}
