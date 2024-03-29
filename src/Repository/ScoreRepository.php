<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\Score;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Score>
 *
 * @method Score|null find($id, $lockMode = null, $lockVersion = null)
 * @method Score|null findOneBy(array $criteria, array $orderBy = null)
 * @method Score[]    findAll()
 * @method Score[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScoreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Score::class);
    }

    public function findByUserAndGame(User $user, Game $game): ?Score
    {
        return $this->findOneBy([
            'user' => $user,
            'game' => $game,
        ]);
    }


    public function findTopScoresForGame($gameId)
{
    return $this->createQueryBuilder('s')
        ->where('s.game = :gameId')
        ->setParameter('gameId', $gameId)
        ->orderBy('s.score', 'DESC')
        ->setMaxResults(3)
        ->getQuery()
        ->getResult();
}

public function findScoresForChart(int $gameId): array
{
    return $this->createQueryBuilder('s')
        ->select('u.username AS username, SUM(s.score) AS totalScore')
        ->join('s.user', 'u')
        ->where('s.game = :gameId')
        ->setParameter('gameId', $gameId)
        ->groupBy('u.username')
        ->getQuery()
        ->getResult();
}

//    /**
//     * @return Score[] Returns an array of Score objects
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

//    public function findOneBySomeField($value): ?Score
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
