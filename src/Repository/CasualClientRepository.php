<?php

namespace App\Repository;

use App\Entity\CasualClient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CasualClient|null find($id, $lockMode = null, $lockVersion = null)
 * @method CasualClient|null findOneBy(array $criteria, array $orderBy = null)
 * @method CasualClient[]    findAll()
 * @method CasualClient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CasualClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CasualClient::class);
    }

    // /**
    //  * @return CasualClient[] Returns an array of CasualClient objects
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


    public function findAllQuery()
    {
        return $this->createQueryBuilder('c')
            ->getQuery();
    }
}
