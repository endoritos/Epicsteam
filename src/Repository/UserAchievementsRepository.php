<?php

namespace App\Repository;

use App\Entity\UserAchievements;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserAchievements>
 *
 * @method UserAchievements|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserAchievements|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserAchievements[]    findAll()
 * @method UserAchievements[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserAchievementsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserAchievements::class);
    }

//    /**
//     * @return UserAchievements[] Returns an array of UserAchievements objects
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

//    public function findOneBySomeField($value): ?UserAchievements
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
