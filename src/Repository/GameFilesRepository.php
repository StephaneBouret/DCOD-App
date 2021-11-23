<?php

namespace App\Repository;

use App\Entity\GameFiles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GameFiles|null find($id, $lockMode = null, $lockVersion = null)
 * @method GameFiles|null findOneBy(array $criteria, array $orderBy = null)
 * @method GameFiles[]    findAll()
 * @method GameFiles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameFilesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameFiles::class);
    }

    // /**
    //  * @return GameFiles[] Returns an array of GameFiles objects
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
    public function findOneBySomeField($value): ?GameFiles
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
