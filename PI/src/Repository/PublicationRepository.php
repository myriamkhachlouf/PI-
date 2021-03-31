<?php

namespace App\Repository;

use App\Entity\Publication;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use function Symfony\Component\DependencyInjection\Loader\Configurator\expr;

/**
 * @method Publication|null find($id, $lockMode = null, $lockVersion = null)
 * @method Publication|null findOneBy(array $criteria, array $orderBy = null)
 * @method Publication[]    findAll()
 * @method Publication[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PublicationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Publication::class);
    }
    public function getstat()
    {
        $emConfig = $this->getEntityManager()->getConfiguration();
        $emConfig->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
        $emConfig->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');
        $emConfig->addCustomDatetimeFunction('DAY', 'DoctrineExtensions\Query\Mysql\Day');
        return $query=$this->createQueryBuilder('p')
            ->select('count(p.id) as num,MONTH(p.createdAt) as month')
            ->where('YEAR(p.createdAt)= YEAR( CURRENT_DATE())')
            ->groupBy('month')
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);
    }
    public function findPostByTitle($title){
        return $this->createQueryBuilder('p')
            ->where('p.title LIKE :title')
            ->setParameter('title', '%'.$title.'%')
            ->getQuery()
            ->getResult();
    }

    public function getPublicationByName(){
        return $query=$this->createQueryBuilder('p')
            ->orderBy('p.title','ASC')
            ->getQuery()
            ->getResult();
    }
    public function getPublicationByDateCreation(){
        return $query=$this->createQueryBuilder('p')
            ->orderBy('p.createdAt','ASC')
            ->getQuery()
            ->getResult();
    }
    public function getPublicationByDateModification(){
        return $query=$this->createQueryBuilder('p')
            ->orderBy('p.updatedAt','DESC')
            ->getQuery()
            ->getResult();
    }
    public function getPublicationByDay(){

        $emConfig = $this->getEntityManager()->getConfiguration();
        $emConfig->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
        $emConfig->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');
        $emConfig->addCustomDatetimeFunction('DAY', 'DoctrineExtensions\Query\Mysql\Day');
        return $query=$this->getEntityManager()->createQuery(
            'SELECT p FROM App\Entity\Publication p 
         WHERE YEAR(p.createdAt) = YEAR( CURRENT_DATE())
         AND MONTH(p.createdAt) = MONTH( CURRENT_DATE())
         AND DAY(p.createdAt) = DAY( CURRENT_DATE())
        ')->getResult();
    }
    public function getPublicationByMonth(){

        $emConfig = $this->getEntityManager()->getConfiguration();
        $emConfig->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
        $emConfig->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');
        $emConfig->addCustomDatetimeFunction('DAY', 'DoctrineExtensions\Query\Mysql\Day');
        return $query=$this->getEntityManager()->createQuery(
            'SELECT p FROM App\Entity\Publication p 
         WHERE YEAR(p.createdAt) = YEAR( CURRENT_DATE())
         AND MONTH(p.createdAt) = MONTH( CURRENT_DATE())
        ')->getResult();
    }
    public function getPublicationByYear(){

        $emConfig = $this->getEntityManager()->getConfiguration();
        $emConfig->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');
        $emConfig->addCustomDatetimeFunction('MONTH', 'DoctrineExtensions\Query\Mysql\Month');
        $emConfig->addCustomDatetimeFunction('DAY', 'DoctrineExtensions\Query\Mysql\Day');
        return $query=$this->getEntityManager()->createQuery(
            'SELECT p FROM App\Entity\Publication p 
         WHERE YEAR(p.createdAt) = YEAR( CURRENT_DATE())
        ')->getResult();
    }


    // /**
    //  * @return Publication[] Returns an array of Publication objects
    //  */
    /*
    public function findByExampleField($value)
    {'DATE_FORMAT("2021-03-16","%Y-%m-%d")' = 'DATE_FORMAT("2021-03-16","%Y-%m-%d")'
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Publication
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
