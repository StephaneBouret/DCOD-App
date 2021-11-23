<?php

namespace App\Repository;

use App\Entity\Alphabet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Alphabet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Alphabet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Alphabet[]    findAll()
 * @method Alphabet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlphabetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Alphabet::class);
    }

    /**
     * Requête qui me permet de récuperer les lettres tiées alphabétiquement
    * @return Alphabet[] Returns an array of Alphabet objects
    */

    public function findAllByAsc()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.letter', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Alphabet
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
