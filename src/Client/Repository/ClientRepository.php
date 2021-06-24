<?php

namespace App\Client\Repository;

use App\Client\Entity\Client;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    // /**
    //  * @return Client[] Returns an array of Client objects
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
    public function findOnlyRegistered()
    {
        return $this->createQueryBuilder('c')
            ->join('c.myRegistration', 'r', 'r.id = c.myRegistration.id')
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findAllByResponsable($id)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.myRegistration.responsableOfRegistration = 7')
            ->getQuery()
            ->getResult();
    }
}
