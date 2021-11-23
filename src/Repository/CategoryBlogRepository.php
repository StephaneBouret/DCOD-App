<?php

namespace App\Repository;

use App\Entity\CategoryBlog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CategoryBlog|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryBlog|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryBlog[]    findAll()
 * @method CategoryBlog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryBlogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryBlog::class);
    }

    public function countPostByCategory()
    {
        return $this->createQueryBuilder('c')
            ->select('c as categories, COUNT(c) as count')
            ->join('c.blogPosts', 'b')
            ->andWhere('b.isActive = true')
            ->groupBy('c')
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?CategoryBlog
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
