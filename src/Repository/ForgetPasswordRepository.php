<?php

namespace App\Repository;

use App\Entity\ForgetPassword;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ForgetPassword|null find($id, $lockMode = null, $lockVersion = null)
 * @method ForgetPassword|null findOneBy(array $criteria, array $orderBy = null)
 * @method ForgetPassword[]    findAll()
 * @method ForgetPassword[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ForgetPasswordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ForgetPassword::class);
    }

    // /**
    //  * @return ForgetPassword[] Returns an array of ForgetPassword objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ForgetPassword
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
