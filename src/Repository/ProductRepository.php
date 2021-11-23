<?php

namespace App\Repository;

use App\Classe\Search;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Requête qui me permet de récuperer les produits en fonction d'une limite
     * @return Product[]
     */
    public function findAllWithLimit()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('RAND()')
            ->setMaxResults(30)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Requête qui me permet de récuperer les produits en fonction de la recherche de l'utilisateur
     * @return Product[]
     */
    public function findWithSearch(Search $search, $page = null, $limit = null)
    {
        $query = $this
            ->createQueryBuilder('p')
            ->select('c', 'l', 'p')
            ->join('p.category', 'c')
            ->leftJoin('p.level', 'l')
            ;
        
        if (!empty($search->string)) {
            $query = $query
                ->andWhere('p.name LIKE :string OR p.subtitle LIKE :string')
                ->setParameter('string', "%{$search->string}%")
                ;
        }

        if (!empty($search->categories)) {
            $query = $query
                ->andWhere('c.id IN (:categories)')
                ->setParameter('categories', $search->categories)
                ;
        }

        if (!empty($search->levels)) {
            $query = $query
                ->andWhere('l.id IN (:levels)')
                ->setParameter('levels', $search->levels)
                ;
        }

        if (!empty($search->tags)) {
            $query = $query
                ->andWhere('c.id IN (:categories)')
                ->setParameter('categories', $search->tags)
                ;
        }

        if (!empty($search->product)) {
            $query = $query
                ->andWhere('p.id IN (:product)')
                ->setParameter('product', $search->product)
                ;
        }

        $query->orderBy('p.name', 'ASC')
            ->setFirstResult(($page * $limit) - $limit)
            ->setMaxResults($limit)
        ;

    //     $query->orderBy('p.id', 'DESC')
    //     ->setFirstResult(($page * $limit) - $limit)
    //     ->setMaxResults($limit)
    // ;

        return $query->getQuery()->getResult();
    }

    /**
     * Returns all Images per page
     * @return Product[] Returns an array of Product objects
     */
    public function getPaginatedImages($page, $limit)
    {
        $query = $this->createQueryBuilder('p')
            ->orderBy('p.name', 'ASC')
            ->setFirstResult(($page * $limit) - $limit)
            ->setMaxResults($limit)
        ;

        return $query->getQuery()->getResult();
    }
    
    /**
     * Returns number of Images
     * @return void
     */
    public function getTotalImages($filterString = null, $filters = null, $filtersLevel = null, $filtersTags = null)
    {
        $query = $this->createQueryBuilder('p')
            ->select('COUNT(p)')
        ;
        // On filtre les données
        if($filterString != null){
            $query
                ->andWhere('p.name LIKE :string')
                ->setParameter('string', "%$filterString%");
        }
        if($filters != null){
            $query->andWhere('p.category IN (:categories)')
                ->setParameter(':categories', array_values($filters));
        }
        if($filtersLevel != null){
            $query->andWhere('p.level IN (:levels)')
                ->setParameter(':levels', array_values($filtersLevel));
        }
        if($filtersTags != null){
            $query->andWhere('p.category IN (:categories)')
                ->setParameter(':categories', array_values($filtersTags));
        }

        return $query->getQuery()->getSingleScalarResult();
    }

    /**
     * Returns images filtered by level and randomly sorted
     * @return Product[] Returns an array of Product objects
     */
    public function findByLevelField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.level  = :val')
            ->setParameter('val', $value)
            // ->orderBy('p.name', 'ASC')
            ->orderBy('RAND()')
            ->setMaxResults(30)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Product[] Returns an array of Product objects
     */
    public function findByCategoryFieldById($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.category  = :val')
            ->setParameter('val', $value)
            ->orderBy('p.name', 'ASC')
            // ->setMaxResults(30)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Product[] Returns an array of Product objects
     */
    public function recentlyAdd()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ->setMaxResults(30)
            ->getResult()
        ;
    }

    /**
     * @return Product[] Returns an array of Product objects
     */
    public function search($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.name LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            // ->setMaxResults(30)
            ->getResult()
        ;
    }

    /**
     * Returns images with likes greater than 5 and sorted by descending likes
     * @return Product[] Returns an array of Product objects
     */
    public function popularFilter()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.likes > :val')
            ->setParameter('val', 5)
            // ->orderBy('p.id', 'DESC')
            ->orderBy('p.likes', 'DESC')
            // ->setMaxResults(30)
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Product
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
