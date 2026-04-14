<?php

namespace App\Repository;

use App\Entity\Sentence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sentence>
 */
class SentenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sentence::class);
    }
    
    public function findByCategory(?int $categoryId) : array
    {
        $queryBuilder = $this->createQueryBuilder('s')
        ->orderBy('s.createdAt', 'DESC');

        if($categoryId) {
            $queryBuilder->andWhere('s.category = :categoryId')
            ->setParameter('categoryId', $categoryId);
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
