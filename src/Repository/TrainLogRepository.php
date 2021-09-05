<?php

namespace App\Repository;

use App\Entity\TrainLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TrainLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrainLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrainLog[]    findAll()
 * @method TrainLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrainLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrainLog::class);
    }

    // /**
    //  * @return TrainLog[] Returns an array of TrainLog objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TrainLog
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
