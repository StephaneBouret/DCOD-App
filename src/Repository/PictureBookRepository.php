<?php

namespace App\Repository;

use App\Entity\PictureBook;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PictureBook|null find($id, $lockMode = null, $lockVersion = null)
 * @method PictureBook|null findOneBy(array $criteria, array $orderBy = null)
 * @method PictureBook[]    findAll()
 * @method PictureBook[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PictureBookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PictureBook::class);
    }

    // /**
    //  * @return PictureBook[] Returns an array of PictureBook objects
    //  */
    /*
    public function findByExampleField($value)
    {
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
    public function findOneBySomeField($value): ?PictureBook
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
