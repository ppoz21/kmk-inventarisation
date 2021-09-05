<?php

namespace App\Repository;

use App\Entity\ToDoList;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ToDoList|null find($id, $lockMode = null, $lockVersion = null)
 * @method ToDoList|null findOneBy(array $criteria, array $orderBy = null)
 * @method ToDoList[]    findAll()
 * @method ToDoList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ToDoListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ToDoList::class);
    }

     /**
      * @return ToDoList[] Returns an array of ToDoList objects
      */
    public function findByUser(User $user, bool $done = false, ?array $orderBy = [])
    {
       $query = $this->createQueryBuilder('t')
            ->where(':user MEMBER OF t.user')
            ->setParameter('user', $user)
           ->andWhere('t.done = :done')
           ->setParameter('done', !$done)
           ->andWhere('t.display = true')
       ;

       foreach ($orderBy as $key=>$value)
       {
           $query->addOrderBy('t.'.$key, $value);
       }

        return $query->getQuery()->getResult();
    }

    /*
    public function findOneBySomeField($value): ?ToDoList
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
