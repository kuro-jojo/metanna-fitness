<?php

namespace App\Client\Subscription\Repository;

use Doctrine\Persistence\ManagerRegistry;
use App\Client\Subscription\Entity\Subscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Subscription|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subscription|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subscription[]    findAll()
 * @method Subscription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subscription::class);
    }
    
        
    /**
     * findAllByResponsable
     *
     * @param  mixed $id
     * @return Subscription []
     */
    public function findAllByResponsable($id) 
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.responsableOfSubs = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }
}
