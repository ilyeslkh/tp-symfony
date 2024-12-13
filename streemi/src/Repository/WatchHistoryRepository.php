<?php

namespace App\Repository;

use App\Entity\WatchHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WatchHistory>
 */
class WatchHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WatchHistory::class);
    }

    /**
     * Trouve les 10 médias les plus regardés.
     *
     * @return WatchHistory[]
     */
    public function findMostViewedMedia(int $limit = 10): array
    {
        return $this->createQueryBuilder('w')
            ->join('w.media', 'm')
            ->groupBy('m.id')
            ->orderBy('SUM(w.numberOfViews)', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les historiques d'un utilisateur spécifique.
     *
     * @param int $userId
     * @return WatchHistory[]
     */
    public function findByUser(int $userId): array
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.watcher = :userId')
            ->setParameter('userId', $userId)
            ->orderBy('w.lastWatchedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les historiques par média.
     *
     * @param int $mediaId
     * @return WatchHistory[]
     */
    public function findByMedia(int $mediaId): array
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.media = :mediaId')
            ->setParameter('mediaId', $mediaId)
            ->orderBy('w.lastWatchedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

         
}
