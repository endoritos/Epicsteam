<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Game>
 *
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function findPrivateGamesForFriends(User $user)
{
    $qb = $this->createQueryBuilder('g')
        ->innerJoin('g.user', 'u')
        ->innerJoin('App\Entity\Friendships', 'f', 'WITH', 
            '(f.requester = :user AND u.id = f.addressee) OR (f.addressee = :user AND u.id = f.requester)')
        ->where('g.isPublic = :isPublic OR g.user = :owner')
        ->andWhere('f.status = :status OR g.user = :owner') 
        ->setParameter('user', $user)
        ->setParameter('isPublic', true)
        ->setParameter('status', 'accepted')
        ->setParameter('owner', $user)
        ->orderBy('g.createdAt', 'DESC'); // to show the most recent games first 
    
    

    return $qb->getQuery()->getResult();
}


public function findPrivateGamesForUser($user)
{
    $qb = $this->createQueryBuilder('g')
        ->where('g.isPublic = true') 
        ->andWhere('g.user = :user') 
        ->setParameter('user', $user)
        ->orderBy('g.createdAt', 'DESC');
    return $qb->getQuery()->getResult();
}

//    /**
//     * @return Game[] Returns an array of Game objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Game
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
