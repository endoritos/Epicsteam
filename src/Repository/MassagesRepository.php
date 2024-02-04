<?php

namespace App\Repository;

use App\Entity\Massages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Massages>
 *
 * @method Massages|null find($id, $lockMode = null, $lockVersion = null)
 * @method Massages|null findOneBy(array $criteria, array $orderBy = null)
 * @method Massages[]    findAll()
 * @method Massages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MassagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Massages::class);
    }

//    /**
//     * @return Massages[] Returns an array of Massages objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Massages
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
