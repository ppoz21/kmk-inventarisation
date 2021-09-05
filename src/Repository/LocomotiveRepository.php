<?php

namespace App\Repository;

use App\Entity\Locomotive;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Locomotive|null find($id, $lockMode = null, $lockVersion = null)
 * @method Locomotive|null findOneBy(array $criteria, array $orderBy = null)
 * @method Locomotive[]    findAll()
 * @method Locomotive[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LocomotiveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Locomotive::class);
    }

    // /**
    //  * @return Locomotive[] Returns an array of Locomotive objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Locomotive
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
