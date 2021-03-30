<?php

namespace App\Repository;

use App\Entity\GrilleEvaluation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GrilleEvaluation|null find($id, $lockMode = null, $lockVersion = null)
 * @method GrilleEvaluation|null findOneBy(array $criteria, array $orderBy = null)
 * @method GrilleEvaluation[]    findAll()
 * @method GrilleEvaluation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GrilleEvaluationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GrilleEvaluation::class);
    }

    // /**
    //  * @return GrilleEvaluation[] Returns an array of GrilleEvaluation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GrilleEvaluation
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
