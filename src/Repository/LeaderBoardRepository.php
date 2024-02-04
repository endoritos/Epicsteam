<?php

namespace App\Repository;

use App\Entity\LeaderBoard;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LeaderBoard>
 *
 * @method LeaderBoard|null find($id, $lockMode = null, $lockVersion = null)
 * @method LeaderBoard|null findOneBy(array $criteria, array $orderBy = null)
 * @method LeaderBoard[]    findAll()
 * @method LeaderBoard[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LeaderBoardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LeaderBoard::class);
    }

//    /**
//     * @return LeaderBoard[] Returns an array of LeaderBoard objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?LeaderBoard
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
