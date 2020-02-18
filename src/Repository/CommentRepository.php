<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * @return Comment[] Returns an array of Comment objects
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function countAjax()
    {
        return $this->createQueryBuilder('c')
            ->select('c.id')
            ->getQuery()
            ->getSingleScalarResult();
    }
    /**
     * @return Comment[] Returns an array of Comment objects
     */
    public function getComment($id, $start, $count)
    {
        return $this->createQueryBuilder('c')
            ->select('c.id, c.content, u.pseudo, p.name as picture, c.created_at')
            ->leftJoin('c.user', 'u')
            ->leftJoin('c.trick', 't')
            ->leftJoin('u.picture', 'p')
            ->setMaxResults($count)
            ->setFirstResult($start)
            ->where('t.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }


    /*
    public function findOneBySomeField($value): ?Comment
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
