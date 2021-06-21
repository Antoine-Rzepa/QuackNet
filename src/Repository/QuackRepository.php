<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Quack;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Quack|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quack|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quack[]    findAll()
 * @method Quack[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quack::class);
    }

    /**
     * @param SearchData $search
     * @return array
     */
    public function findSearch(SearchData $search): array
    {

        $query = $this
            ->createQueryBuilder('quack')
            // N'afficherait que les tags / author selectionnés
            //->select('quack', 'tag')
            ->where('quack.quack IS null')
            ->join('quack.author', 'author')
            ->join('quack.tags', 'tag')
        ;

        if(!empty($search->quack)){
            $query = $query
                ->andWhere('author.duckname LIKE :author')
                ->setParameter('author', "%{$search->quack}%");
        }

        if(!empty($search->tags)){
            $query = $query
                ->andWhere('tag.id IN (:tag)')
                ->setParameter('tag', $search->tags);
        }

        return $query->getQuery()->getResult();
    }

    // /**
    //  * @return Quack[] Returns an array of Quack objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Quack
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
