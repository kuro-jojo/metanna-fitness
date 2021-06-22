<?php

namespace App\Repository;

use App\Entity\AmountSetting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AmountSetting|null find($id, $lockMode = null, $lockVersion = null)
 * @method AmountSetting|null findOneBy(array $criteria, array $orderBy = null)
 * @method AmountSetting[]    findAll()
 * @method AmountSetting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AmountSettingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AmountSetting::class);
    }

    // /**
    //  * @return AmountSetting[] Returns an array of AmountSetting objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AmountSetting
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
