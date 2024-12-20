<?php

namespace App\Repository;

use App\Entity\Episode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Episode>
 */
class EpisodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Episode::class);
    }


    public function findRecentEpisodes(int $maxResults): array
{
    return $this->createQueryBuilder('e')
        ->orderBy('e.releasedAt', 'DESC')
        ->setMaxResults($maxResults)
        ->getQuery()
        ->getResult();
}

public function findLongestEpisodes(int $maxResults): array
{
    return $this->createQueryBuilder('e')
        ->orderBy('e.duration', 'DESC')
        ->setMaxResults($maxResults)
        ->getQuery()
        ->getResult();
}

public function findEpisodesByKeyword(string $keyword): array
{
    return $this->createQueryBuilder('e')
        ->andWhere('e.title LIKE :keyword')
        ->setParameter('keyword', '%' . $keyword . '%')
        ->orderBy('e.releasedAt', 'DESC')
        ->getQuery()
        ->getResult();
}


    //    /**
    //     * @return Episode[] Returns an array of Episode objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Episode
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}