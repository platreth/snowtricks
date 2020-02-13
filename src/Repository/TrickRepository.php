<?php

namespace App\Repository;

use App\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Trick|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trick|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trick[]    findAll()
 * @method Trick[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrickRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trick::class);
    }

     /**
      * @return Trick[] Returns an array of Trick objects
      */
    public function findByTrickAjax($first, $max)
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.id', 'DESC')
            ->setFirstResult($first)
            ->setMaxResults($max)
            ->getQuery()
            ->getResult()
        ;
    }
     /**
      * @return Trick[] Returns an array of Trick objects
      */
    public function findBySlug($slug)
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.id', 'DESC')
            ->andWhere('t.slug = :val')
            ->setParameter('val', $slug)
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?Trick
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
