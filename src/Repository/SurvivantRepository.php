<?php

namespace App\Repository;

use App\Entity\Survivant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SurvivantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Survivant::class);
    }

    /**
     * Z–A : ordre alphabétique inverse
     */
    public function findByNameDesc(): array
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.nom', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Tous les nains
     */
    public function findNains(): array
    {
        return $this->createQueryBuilder('s')
            ->join('s.race', 'r')
            ->where('r.race_name = :race')
            ->setParameter('race', 'Nain')
            ->getQuery()
            ->getResult();
    }

    /**
     * Elf avec puissance >= X
     */
    public function findElfPuissanceMin(int $min): array
    {
        return $this->createQueryBuilder('s')
            ->join('s.race', 'r')
            ->where('r.race_name = :race')
            ->andWhere('s.puissance >= :min')
            ->setParameter('race', 'Elf')
            ->setParameter('min', $min)
            ->getQuery()
            ->getResult();
    }



    public function findArcherNonHumain(): array
    {
        return $this->createQueryBuilder('s')
            ->join('s.classe', 'c')
            ->join('s.race', 'r')
            ->where('c.class_name = :classe')
            ->andWhere('r.race_name != :race')
            ->setParameter('classe', 'Archer')
            ->setParameter('race', 'Humain')
            ->getQuery()
            ->getResult();
    }


    /**
     * Filtres via formulaire
     */
    public function filterByForm(
        ?int $puissance,
        ?string $race,
        ?string $classe
    ): array {
        $qb = $this->createQueryBuilder('s')
            ->join('s.race', 'r')
            ->join('s.classe', 'c');

        if ($puissance !== null) {
            $qb->andWhere('s.puissance >= :p')
               ->setParameter('p', $puissance);
        }

        if ($race) {
            $qb->andWhere('r.race_name = :race')
               ->setParameter('race', $race);
        }

        if ($classe) {
            $qb->andWhere('c.class_name = :classe')
               ->setParameter('classe', $classe);
        }

        return $qb->getQuery()->getResult();
    }


    /**
     * Puissance cumulée par race
     */
    public function getPowerByRace(): array
    {
        return $this->createQueryBuilder('s')
            ->select('r.race_name AS race, SUM(s.puissance) AS total')
            ->join('s.race', 'r')
            ->groupBy('r.race_name')
            ->getQuery()
            ->getResult();
    }
}
