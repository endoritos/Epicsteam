<?php

namespace App\Repository;

use App\Entity\Friendships;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends ServiceEntityRepository<Friendships>
 *
 * @method Friendships|null find($id, $lockMode = null, $lockVersion = null)
 * @method Friendships|null findOneBy(array $criteria, array $orderBy = null)
 * @method Friendships[]    findAll()
 * @method Friendships[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FriendshipsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Friendships::class);
    }

    public function findFriendRequestsByUser(User $user)
{
    return $this->createQueryBuilder('f')
        ->where('f.addressee = :user')
        ->andWhere('f.status = :status')
        ->setParameter('user', $user)
        ->setParameter('status', 'pending')
        ->getQuery()
        ->getResult();
}

public function findFriendshipStatus($requesterId, $addresseeId): ?string {
    $qb = $this->createQueryBuilder('f')
        ->select('f.status')
        ->where('f.requester = :requesterId AND f.addressee = :addresseeId')
        ->setParameter('requesterId', $requesterId)
        ->setParameter('addresseeId', $addresseeId)
        ->setMaxResults(1);

    $query = $qb->getQuery();
    $result = $query->getOneOrNullResult();

    return $result ? $result['status'] : null;
}

public function getReceivedFriendRequests(User $user)
{
    $qb = $this->createQueryBuilder('f');
    $qb->where('f.addressee = :user')
       ->setParameter('user', $user);

    return $qb->getQuery()->getResult();
}


public function findAcceptedFriendships(User $user)
{
    $qb = $this->createQueryBuilder('f');
    $qb->where('f.status = :status')
       ->andWhere('f.requester = :user OR f.addressee = :user')
       ->setParameter('status', 'accepted')
       ->setParameter('user', $user);

    return $qb->getQuery()->getResult();
}


public function findAcceptedFriendship(User $user, User $otherUser): ?Friendships
{
    $qb = $this->createQueryBuilder('f')
        ->where('(f.requester = :user AND f.addressee = :otherUser) OR (f.requester = :otherUser AND f.addressee = :user)')
        ->andWhere('f.status = :status')
        ->setParameter('user', $user)
        ->setParameter('otherUser', $otherUser)
        ->setParameter('status', 'accepted')
        ->setMaxResults(1);

    return $qb->getQuery()->getOneOrNullResult();
}
//    /**
//     * @return Friendships[] Returns an array of Friendships objects
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

//    public function findOneBySomeField($value): ?Friendships
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
