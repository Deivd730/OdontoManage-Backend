<?php

namespace App\Repository;

use App\Entity\ToothPathology;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ToothPathology>
 *
 * @method ToothPathology|null find($id, $lockMode = null, $lockVersion = null)
 * @method ToothPathology|null findOneBy(array $criteria, array $orderBy = null)
 * @method ToothPathology[]    findAll()
 * @method ToothPathology[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ToothPathologyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ToothPathology::class);
    }
}
