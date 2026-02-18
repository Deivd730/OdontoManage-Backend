<?php

namespace App\Repository;

use App\Entity\Pathology;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pathology>
 *
 * @method Pathology|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pathology|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pathology[]    findAll()
 * @method Pathology[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PathologyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pathology::class);
    }
}
