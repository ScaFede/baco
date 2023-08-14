<?php

namespace App\Repository;

use App\Entity\CompetenzeBis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CompetenzeBis>
 *
 * @method CompetenzeBis|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompetenzeBis|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompetenzeBis[]    findAll()
 * @method CompetenzeBis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompetenzeBisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompetenzeBis::class);
    }

    public function save(CompetenzeBis $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CompetenzeBis $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByCategory($categoryId)
    {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.categorieRelation', 'cat') // Associazione in CompetenzeBis
            ->andWhere('cat.id = :categoryId')
            ->setParameter('categoryId', $categoryId)
            ->getQuery();

        return $qb->getResult();
    }



    public function findBySearchTerm(string $searchTerm): array
      {
          $qb = $this->createQueryBuilder('c');

          if ($searchTerm) {
              $qb->andWhere($qb->expr()->orX(
                  $qb->expr()->like('c.Titolo', ':searchTerm'),
                  $qb->expr()->like('c.Descrizione', ':searchTerm')
              ))
              ->setParameter('searchTerm', '%' . $searchTerm . '%');
          }

          return $qb->getQuery()->getResult();
      }


      public function findByCity($cityId)
      {
          $qb = $this->createQueryBuilder('c')
              ->leftJoin('c.UserRelation', 'u')
              ->leftJoin('u.cittaRel', 'ci')
              ->where('ci.id = :cityId')
              ->setParameter('cityId', $cityId)
              ->getQuery();

          return $qb->getResult();
      }


//    /**
//     * @return CompetenzeBis[] Returns an array of CompetenzeBis objects
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

//    public function findOneBySomeField($value): ?CompetenzeBis
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
