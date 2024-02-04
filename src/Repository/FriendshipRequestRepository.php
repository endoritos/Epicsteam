<?php

namespace App\Repository;

use App\Entity\FriendshipRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FriendshipRequest>
 *
 * @method FriendshipRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method FriendshipRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method FriendshipRequest[]    findAll()
 * @method FriendshipRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FriendshipRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FriendshipRequest::class);
    }

//    /**
//     * @return FriendshipRequest[] Returns an array of FriendshipRequest objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FriendshipRequest
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
