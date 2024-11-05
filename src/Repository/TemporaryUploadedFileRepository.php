<?php

namespace App\Repository;

use App\Entity\TemporaryUploadedFile;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

/**
 * @extends ServiceEntityRepository<TemporaryUploadedFile>
 */
class TemporaryUploadedFileRepository extends ServiceEntityRepository
{
    private LoggerInterface $logger;

    public function __construct(ManagerRegistry $registry, LoggerInterface $logger)
    {
        parent::__construct($registry, TemporaryUploadedFile::class);
        $this->logger = $logger;
    }


    public function findByFileName(User $user, string $fileName)
    {
        try {
            return $this->createQueryBuilder('t')
                ->where('t.file_name = :fileName')
                ->andWhere('t.uploader = :user') // Assuming `uploader` is a relation field in the entity
                ->setParameter('fileName', $fileName)
                ->setParameter('user', $user)
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();

        } catch (\Throwable $th) {
            $this->logger->error($th->getMessage());
            return $th;
        }
    }

    //    /**
    //     * @return TemporaryUploadedFile[] Returns an array of TemporaryUploadedFile objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?TemporaryUploadedFile
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
