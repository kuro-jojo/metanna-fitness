<?php

namespace App\Repository;

use App\Entity\DailyClients;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DailyClients|null find($id, $lockMode = null, $lockVersion = null)
 * @method DailyClients|null findOneBy(array $criteria, array $orderBy = null)
 * @method DailyClients[]    findAll()
 * @method DailyClients[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DailyClientsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DailyClients::class);
    }

    // /**
    //  * @return DailyClients[] Returns an array of DailyClients objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DailyClients
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
