<?php

namespace App\Repository;

use App\Entity\Media;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Media>
 */
class MediaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Media::class);
    }

    /**
     * Find the most popular media up to the given limit.
     *
     * @param int $maxResults The maximum number of results to return.
     * @return Media[] Returns an array of popular Media objects.
     */
    public function findPopular(int $maxResults): array
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.popularity', 'DESC') // Assuming 'popularity' is a field in the Media entity
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find media by a specific tag.
     *
     * @param string $tag The tag to filter by.
     * @return Media[] Returns an array of Media objects filtered by the tag.
     */
    public function findByTag(string $tag): array
    {
        return $this->createQueryBuilder('m')
            ->join('m.tags', 't') // Assuming there is a tags relation
            ->andWhere('t.name = :tag')
            ->setParameter('tag', $tag)
            ->orderBy('m.createdAt', 'DESC') // Assuming 'createdAt' is a field in the Media entity
            ->getQuery()
            ->getResult();
    }

    /**
     * Find recently added media.
     *
     * @param int $maxResults The maximum number of results to return.
     * @return Media[] Returns an array of recently added Media objects.
     */
    public function findRecent(int $maxResults): array
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.createdAt', 'DESC') // Assuming 'createdAt' is a field in the Media entity
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult();
    }

    /**
     * Find media by author.
     *
     * @param int $authorId The ID of the author.
     * @return Media[] Returns an array of Media objects created by the specified author.
     */
    public function findByAuthor(int $authorId): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.author = :authorId')
            ->setParameter('authorId', $authorId)
            ->orderBy('m.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Search media by keyword in title or description.
     *
     * @param string $keyword The keyword to search for.
     * @return Media[] Returns an array of Media objects matching the keyword.
     */
    public function searchByKeyword(string $keyword): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.title LIKE :keyword OR m.description LIKE :keyword')
            ->setParameter('keyword', '%' . $keyword . '%')
            ->orderBy('m.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
