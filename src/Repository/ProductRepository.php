<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

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
     *  all products
     *
     * @param int           $pageNumber
     * @param int $limit    number per page
     * @return Paginator    limit per page
     */
    public function findProducts(int $pageNumber , int $limit  ) :Paginator{


        $qb = $this->createQueryBuilder('p')
            ->join('p.category','c')
            ->addSelect('c.name as cat, p.name,p.id,p.price,p.description')
            ->orderBy('p.id',"DESC")
            ->getQuery()
            ->setFirstResult(($pageNumber-1) * $limit) // On définit l'annonce à partir de laquelle commencer la liste
            // Ainsi que le nombre d'annonce à afficher sur une page
            ->setMaxResults($limit);


        return new Paginator( $qb, true);
    }

    /**
     * @param int $productId
     */
    public function findProductById(int $productId ) {

        try {
            return $this->createQueryBuilder('p')
                ->select('c.name as cat, p.name,p.id,p.price,p.description')
                ->join('p.category', 'c')
                ->where('p.id=:id')
                ->setParameter('id', $productId)
                ->getQuery()
                ->setMaxResults(1)
                ->getSingleResult();
        } catch (NoResultException | NonUniqueResultException $e) {
            return null;
        }
    }
}
