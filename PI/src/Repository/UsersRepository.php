<?php

namespace App\Repository;

use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Controller\NormalizerInterface;


/**
 * @method Users|null find($id, $lockMode = null, $lockVersion = null)
 * @method Users|null findOneBy(array $criteria, array $orderBy = null)
 * @method Users[]    findAll()
 * @method Users[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Users::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof Users) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    // /**
    //  * @return Users[] Returns an array of Users objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Users
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function getUserByNom()
    {
        return $query=$this->createQueryBuilder('p')
            ->orderBy('p.nom','ASC')
            ->getQuery()
            ->getResult();
    }

    public function getUserByEmail()
    {
        return $query=$this->createQueryBuilder('p')
            ->orderBy('p.email','ASC')
            ->getQuery()
            ->getResult();
    }

    public function getUserByTelephone()
    {
        return $query=$this->createQueryBuilder('p')
            ->orderBy('p.telephone','ASC')
            ->getQuery()
            ->getResult();
    }

    public function getUserByDomaine()
    {
        return $query=$this->createQueryBuilder('p')
            ->orderBy('p.domaine','ASC')
            ->getQuery()
            ->getResult();
    }

    public function getUserByAdresse()
    {
        return $query=$this->createQueryBuilder('p')
            ->orderBy('p.adresse','ASC')
            ->getQuery()
            ->getResult();
    }
    public function findUserByName($nom){
        return $this->createQueryBuilder('u')
            ->where('u.nom LIKE :nom')
            ->setParameter('nom', '%'.$nom.'%')
            ->getQuery()
            ->getResult();
    }
    public function statistiqueDomaineUser(){
        $query=$this->getEntityManager()
            ->createQuery('Select m.domaine , count(m.id) as nbusers From App\Entity\Users m Group By m.domaine');
        return $query->getResult();
    }
}
