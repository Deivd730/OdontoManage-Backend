<?php

namespace App\Repository;

use App\Entity\Dentist;
use App\Entity\Treatment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<Dentist>
 *
 * @method Dentist|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dentist|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dentist[]    findAll()
 * @method Dentist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DentistRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dentist::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Dentist) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    /**
     * @return Dentist[]
     */
    public function findByTreatmentAndWorkingDate(Treatment $treatment, \DateTimeInterface $visitDate): array
    {
        $dentists = $this->createQueryBuilder('d')
            ->innerJoin('d.treatments', 't')
            ->andWhere('t = :treatment')
            ->setParameter('treatment', $treatment)
            ->orderBy('d.lastName', 'ASC')
            ->addOrderBy('d.firstName', 'ASC')
            ->getQuery()
            ->getResult();

        $dayOfWeek = $visitDate->format('D');

        return array_values(array_filter(
            $dentists,
            static function (Dentist $dentist) use ($dayOfWeek): bool {
                $availableDays = $dentist->getAvailableDays();

                if (!$availableDays) {
                    return true;
                }

                $availableDaysArray = array_map('trim', explode(',', $availableDays));

                return in_array($dayOfWeek, $availableDaysArray, true);
            }
        ));
    }

    /**
     * @return Treatment[]
     */
    public function findTreatmentsForDentist(Dentist $dentist): array
    {
        $treatments = $dentist->getTreatments()->toArray();

        usort(
            $treatments,
            static fn (Treatment $a, Treatment $b): int => strcmp((string) $a->getName(), (string) $b->getName())
        );

        return $treatments;
    }
}
