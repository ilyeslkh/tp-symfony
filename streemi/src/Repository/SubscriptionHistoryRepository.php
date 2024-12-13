<?php

namespace App\Repository;

use App\Entity\Subscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Subscription>
 */
class SubscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subscription::class);
    }

    /**
     * Find subscriptions with a price lower than or equal to the given value.
     *
     * @param int $maxPrice
     * @return Subscription[]
     */
    public function findAffordableSubscriptions(int $maxPrice): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.price <= :maxPrice')
            ->setParameter('maxPrice', $maxPrice)
            ->orderBy('s.price', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find subscriptions ordered by duration (longest first).
     *
     * @return Subscription[]
     */
    public function findSubscriptionsByDuration(): array
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.duration', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find a subscription by name.
     *
     * @param string $name
     * @return Subscription|null
     */
    public function findByName(string $name): ?Subscription
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Find subscriptions with the most users.
     *
     * @param int $maxResults
     * @return Subscription[]
     */
    public function findPopularSubscriptions(int $maxResults): array
    {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.users', 'u')
            ->groupBy('s.id')
            ->orderBy('COUNT(u.id)', 'DESC')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult();
    }
}
