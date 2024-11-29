<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }
    public function countProductsCategory(): array
    {
        return $this->createQueryBuilder('p')
            ->select('c.name as category, count(p.id) as count')
            ->join('p.category', 'c')
            ->groupBy('c.id')
            ->getQuery()
            ->getResult();
    }
    public function countProductStatus(): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.status as status, count(p.id) as count')
            ->groupBy('p.status')
            ->getQuery()
            ->getResult();
    }
    // src/Repository/ProductRepository.php
public function findByPartialName(string $name): array
{
    return $this->createQueryBuilder('p')
        ->select('p.id, p.name, p.price') // On sélectionne seulement les données nécessaires
        ->where('p.name LIKE :name')
        ->setParameter('name', '%' . $name . '%')
        ->setMaxResults(10) // Limite les résultats pour améliorer les performances
        ->getQuery()
        ->getArrayResult(); // Retourne un tableau associatif pour l'API JSON
}


    //    /**
    //     * @return Product[] Returns an array of Product objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
