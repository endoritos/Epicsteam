<?php

namespace App\Repository;

use App\Entity\Message;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Message>
 *
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    
public function findMessagesBetweenUsers(int $userId1, int $userId2): array
{
    $qb = $this->createQueryBuilder('m');
    
    // Assuming 'sender' and 'receiver' are the fields in your Message entity
    $qb->where($qb->expr()->orX(
            $qb->expr()->andX(
                $qb->expr()->eq('m.sender', ':user1'),
                $qb->expr()->eq('m.receiver', ':user2')
            ),
            $qb->expr()->andX(
                $qb->expr()->eq('m.sender', ':user2'),
                $qb->expr()->eq('m.receiver', ':user1')
            )
        ))
        ->setParameter('user1', $userId1)
        ->setParameter('user2', $userId2)
        ->orderBy('m.createdAt', 'ASC'); // or DESC based on how you want to order them

    return $qb->getQuery()->getResult();
}


    public function countUnreadMessagesByUser(User $user): int
    {
        $qb = $this->createQueryBuilder('m')
            ->select('count(m.id)')
            ->where('m.receiver = :user')
            ->andWhere('m.isRead = :isRead')
            ->setParameter('user', $user)
            ->setParameter('isRead', false);

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

//    /**
//     * @return Message[] Returns an array of Message objects
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

//    public function findOneBySomeField($value): ?Message
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
