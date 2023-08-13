<?php

namespace App\Repository;

use App\Entity\Citta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Citta>
 *
 * @method Citta|null find($id, $lockMode = null, $lockVersion = null)
 * @method Citta|null findOneBy(array $criteria, array $orderBy = null)
 * @method Citta[]    findAll()
 * @method Citta[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CittaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Citta::class);
    }

//    /**
//     * @return Citta[] Returns an array of Citta objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Citta
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
