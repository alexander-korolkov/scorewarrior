<?php

namespace App\Repository;

use App\Entity\PlayerMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PlayerMessage>
 *
 * @method PlayerMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlayerMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlayerMessage[]    findAll()
 * @method PlayerMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayerMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlayerMessage::class);
    }

    //    /**
    //     * @return PlayerMessage[] Returns an array of PlayerMessage objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?PlayerMessage
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
