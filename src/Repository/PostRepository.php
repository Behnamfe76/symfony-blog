<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function getUnpublishedPostList(int $page = 1, int $limit = 10): array
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->select(
                'p.id',
                'p.title',
                'pc.name AS category_name',
                'pt.name AS type_name',
                'pa.username AS author_name',
                'a.changed_to as status'
            )
            ->leftJoin('p.approval', 'a')
            ->leftJoin('p.author', 'pa')
            ->leftJoin('p.postCategory', 'pc')
            ->leftJoin('p.postType', 'pt')
            ->where('a.changed_to != :status')
            ->setParameter('status', 'published')
            ->orderBy('p.created_at', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        $query = $queryBuilder->getQuery();
        $results = $query->getScalarResult();


        $totalQueryBuilder = $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->leftJoin('p.approval', 'a')
            ->where('a.changed_to != :status')
            ->setParameter('status', 'published');
        $total = (int) $totalQueryBuilder->getQuery()->getSingleScalarResult();

        return [
            'data' => $results,
            'total' => $total,
        ];
    }

    public function getAllPostList(int $page = 1, int $limit = 10): array
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->select('p.title',
                'pc.name AS category_name',
                'pt.name AS type_name',
                'pa.username AS author_name',
                'a.changed_to as status'
            )
            ->leftJoin('p.approval', 'a')
            ->leftJoin('p.author', 'pa')
            ->leftJoin('p.postCategory', 'pc')
            ->leftJoin('p.postType', 'pt')
            ->orderBy('p.created_at', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        $query = $queryBuilder->getQuery();
        $results = $query->getScalarResult();


        $totalQueryBuilder = $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->leftJoin('p.approval', 'a')
            ->where('a.changed_to != :status')
            ->setParameter('status', 'published');
        $total = (int) $totalQueryBuilder->getQuery()->getSingleScalarResult();

        return [
            'data' => $results,
            'total' => $total,
        ];
    }
}
