<?php

namespace App\Repository;

use App\Entity\ClientCard;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ClientCard|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientCard|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientCard[]    findAll()
 * @method ClientCard[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientCardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClientCard::class);
    }

    // /**
    //  * @return ClientCard[] Returns an array of ClientCard objects
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
    public function findOneBySomeField($value): ?ClientCard
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
