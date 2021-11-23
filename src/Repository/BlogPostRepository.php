<?php

namespace App\Repository;

use App\Classe\Search;
use App\Entity\BlogPost;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method BlogPost|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlogPost|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlogPost[]    findAll()
 * @method BlogPost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogPostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlogPost::class);
    }

    public function findPostsValidated()
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.isActive = true')
            ->groupBy('b')
            ->orderBy('b.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Blogpost[] Returns an array of Blogpost objects
     */
    public function lastPost()
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.isActive = true')
            ->orderBy('b.id', 'DESC')
            ->getQuery()
            ->setMaxResults(5)
            ->getResult()
        ;
    }

    /**
     * @return Blogpost[] Returns an array of Blogpost objects
     */
    public function findByCategoryPostById($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.categoryPost  = :val')
            ->setParameter('val', $value)
            ->andWhere('b.isActive = true')
            ->orderBy('b.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Requête qui me permet de récuperer les posts en fonction de la recherche de l'utilisateur
     * @return BlogPost[]
     */
    public function findWithSearch(Search $search, $page = null, $limit = null)
    {
        $query = $this
            ->createQueryBuilder('b')
            ->select('b')
            ->andWhere('b.isActive = true')
            ;
        
        if (!empty($search->string)) {
            $query = $query
                ->andWhere('b.title LIKE :string')
                ->setParameter('string', "%{$search->string}%")
                ;
        }

        $query->orderBy('b.id', 'DESC')
            ->setFirstResult(($page * $limit) - $limit)
            ->setMaxResults($limit)
        ;
        return $query->getQuery()->getResult();
    }

    // /**
    //  * @return Blogpost[] Returns an array of Blogpost objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Blogpost
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
