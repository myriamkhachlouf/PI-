<?php

namespace App\Repository;

use App\Entity\Reclamation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reclamation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reclamation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reclamation[]    findAll()
 * @method Reclamation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReclamationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reclamation::class);
    }


    function findByEtat($objet)
    {
        return $this->createQueryBuilder('reclamation')
            ->where('reclamation.objet  LIKE :objet ')
            ->setParameter('objet', '%'.$objet.'%')
            ->getQuery()->getResult();
    }

    function OrderByDateQB()
    {
        return $this->createQueryBuilder('s')

            ->orderBy('s.date_rec', 'ASC')
            ->getQuery()->getResult();
    }

}
