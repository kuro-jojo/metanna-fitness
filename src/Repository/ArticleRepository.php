<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * findByCategoryQuery
     *
     * @param  mixed $category
     * @return Query
     */
    public function findByCategoryQuery($category) : Query
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.category = :val')
            ->setParameter('val', $category)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
        ;
    }
    

    
    public function findByLabel($label)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.label LIKE :val')
            ->setParameter('val', '%'.$label.'%')
            ->getQuery()
            ->getResult()
        ;
    }
}
