<?php

namespace App\Repository;

use App\Entity\Car;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Car>
 */
class CarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Car::class);
    }

    public function findAllUniqueBrands(): array
    {
        $query = $this->createQueryBuilder('c')
            ->select('DISTINCT c.brand')
            ->where('c.brand IS NOT NULL')
            ->orderBy('c.brand', 'ASC')
            ->getQuery();
        // array_column transforme le résultat [['brand' => 'Peugeot'], ['brand' => 'Renault']] 
        // en un tableau simple ['Peugeot', 'Renault']
        return array_column($query->getResult(), 'brand');
    }

    public function findModelsByBrand(string $brand): array
    {
        return $this->createQueryBuilder('c')
            ->select('DISTINCT c.model')
            ->where('c.brand = :brand')
            ->setParameter('brand', $brand)
            ->orderBy('c.model', 'ASC')
            ->getQuery()
            ->getScalarResult(); // Récupère un tableau plat
    }

    public function findByFilters($data)
    {
        $query = $this->createQueryBuilder('c');

        if (!empty($data['brand'])) {
            $query->andWhere('c.brand = :brand')
                ->setParameter('brand', $data['brand']);
        }

        if (!empty($data['model'])) {
            $query->andWhere('c.model = :model')
                ->setParameter('model', $data['model']);
        }

        if (!empty($data['minYear'])) {
            $query->andWhere('c.year >= :minYear')
                ->setParameter('minYear', $data['minYear']);
        }

        if (!empty($data['maxPrice'])) {
            $query->andWhere('c.price/100 <= :maxPrice')
                ->setParameter('maxPrice', $data['maxPrice']);
        }

        return $query->getQuery()->getResult();
    }
}
