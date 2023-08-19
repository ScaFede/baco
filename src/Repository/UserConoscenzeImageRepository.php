<?php

namespace App\Repository;

use App\Entity\UserConoscenzeImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserConoscenzeImage>
 *
 * @method UserConoscenzeImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserConoscenzeImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserConoscenzeImage[]    findAll()
 * @method UserConoscenzeImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserConoscenzeImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserConoscenzeImage::class);
    }

//    /**
//     * @return UserConoscenzeImage[] Returns an array of UserConoscenzeImage objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserConoscenzeImage
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
