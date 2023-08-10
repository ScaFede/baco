<?php

namespace App\Repository;

use App\Entity\Scambi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;


/**
 * @extends ServiceEntityRepository<Scambi>
 *
 * @method Scambi|null find($id, $lockMode = null, $lockVersion = null)
 * @method Scambi|null findOneBy(array $criteria, array $orderBy = null)
 * @method Scambi[]    findAll()
 * @method Scambi[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScambiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Scambi::class);
    }

    public function save(Scambi $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Scambi $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//implemento metodo per cercare scambi
    public function findProposteInviate(User $user): array
   {
       return $this->createQueryBuilder('s')
           //->andWhere('s.fromUser = :user')
           ->andWhere('s.userSender = :user')
           ->setParameter('user', $user)
           ->getQuery()
           ->getResult();
   }


   public function findProposteRicevute(User $user): array
   {
       return $this->createQueryBuilder('s')
           ->join('s.userTarget', 'u')
           ->andWhere('u = :user')
           ->setParameter('user', $user)
           ->getQuery()
           ->getResult();
   }


   public function findScambiByUser(User $user): array
{
    return $this->createQueryBuilder('s')
        ->andWhere(':user MEMBER OF s.userTarget OR s.userSender = :user')
        ->setParameter('user', $user)
        ->getQuery()
        ->getResult();
}


//    /**
//     * @return Scambi[] Returns an array of Scambi objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Scambi
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
