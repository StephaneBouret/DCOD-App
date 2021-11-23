<?php

namespace App\Repository;

use App\Entity\ValidateRegister;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ValidateRegister|null find($id, $lockMode = null, $lockVersion = null)
 * @method ValidateRegister|null findOneBy(array $criteria, array $orderBy = null)
 * @method ValidateRegister[]    findAll()
 * @method ValidateRegister[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ValidateRegisterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ValidateRegister::class);
    }

    // /**
    //  * @return ValidateRegister[] Returns an array of ValidateRegister objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ValidateRegister
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
