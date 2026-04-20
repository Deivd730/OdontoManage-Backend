<?php

namespace App\Repository;

use App\Entity\BridgeTreatment;
use App\Entity\Odontogram;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BridgeTreatment>
 */
class BridgeTreatmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BridgeTreatment::class);
    }

    /**
     * Find all bridge treatments for an odontogram
     */
    public function findByOdontogram(Odontogram $odontogram): array
    {
        return $this->findBy(['odontogram' => $odontogram]);
    }

    /**
     * Get all teeth in a bridge range (inclusive)
     * Example: startTooth=12, endTooth=14 returns [12, 13, 14]
     */
    public function getTeethInBridgeRange(int $startTooth, int $endTooth): array
    {
        if ($startTooth > $endTooth) {
            [$startTooth, $endTooth] = [$endTooth, $startTooth];
        }

        $teeth = [];
        for ($tooth = $startTooth; $tooth <= $endTooth; $tooth++) {
            $teeth[] = $tooth;
        }

        return $teeth;
    }

    /**
     * Check if there's already a bridge for the same start/end teeth and treatment
     */
    public function existsBridgeBetweenTeeth(Odontogram $odontogram, int $treatmentId, int $startTooth, int $endTooth): bool
    {
        if ($startTooth > $endTooth) {
            [$startTooth, $endTooth] = [$endTooth, $startTooth];
        }

        $result = $this->createQueryBuilder('bt')
            ->andWhere('bt.odontogram = :odontogram')
            ->andWhere('bt.treatment = :treatmentId')
            ->andWhere('bt.startTooth = :startTooth')
            ->andWhere('bt.endTooth = :endTooth')
            ->setParameter('odontogram', $odontogram)
            ->setParameter('treatmentId', $treatmentId)
            ->setParameter('startTooth', $startTooth)
            ->setParameter('endTooth', $endTooth)
            ->getQuery()
            ->getOneOrNullResult();

        return $result !== null;
    }
}
