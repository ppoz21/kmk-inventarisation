<?php

namespace App\Repository;

use App\Entity\StationLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StationLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method StationLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method StationLog[]    findAll()
 * @method StationLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StationLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StationLog::class);
    }

    // /**
    //  * @return StationLog[] Returns an array of StationLog objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StationLog
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
