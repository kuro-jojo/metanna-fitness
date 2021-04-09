<?php

namespace App\Repository;

use App\Entity\ClientActivities;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ClientActivities|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientActivities|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientActivities[]    findAll()
 * @method ClientActivities[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientActivitiesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClientActivities::class);
    }

    // /**
    //  * @return ClientActivities[] Returns an array of ClientActivities objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ClientActivities
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
