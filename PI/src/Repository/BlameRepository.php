<?php

namespace App\Repository;

use App\Entity\Blame;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Blame|null find($id, $lockMode = null, $lockVersion = null)
 * @method Blame|null findOneBy(array $criteria, array $orderBy = null)
 * @method Blame[]    findAll()
 * @method Blame[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Blame::class);
    }

    // /**
    //  * @return Blame[] Returns an array of Blame objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Blame
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
