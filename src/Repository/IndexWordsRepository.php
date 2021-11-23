<?php

namespace App\Repository;

use App\Entity\IndexWords;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method IndexWords|null find($id, $lockMode = null, $lockVersion = null)
 * @method IndexWords|null findOneBy(array $criteria, array $orderBy = null)
 * @method IndexWords[]    findAll()
 * @method IndexWords[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IndexWordsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IndexWords::class);
    }

    /**
     * @return IndexWords[] Returns an array of Product objects
     */
    public function findByAlphabetFieldBySlug($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.alphabet  = :val')
            ->setParameter('val', $value)
            ->orderBy('i.title', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return IndexWords[] Returns an array of IndexWords objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?IndexWords
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
