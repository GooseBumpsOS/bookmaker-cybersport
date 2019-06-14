<?php

namespace App\Repository;

use App\Entity\BookmakerRating;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method BookmakerRating|null find($id, $lockMode = null, $lockVersion = null)
 * @method BookmakerRating|null findOneBy(array $criteria, array $orderBy = null)
 * @method BookmakerRating[]    findAll()
 * @method BookmakerRating[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookmakerRatingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BookmakerRating::class);
    }

    public function getAvgOfMark($name) : float {

        $qb = $this
            ->createQueryBuilder('p')
            ->select('avg(p.mark)')
            ->where('p.bookmaker_name = :name')
            ->setParameter('name', $name)
            ->getQuery()
        ;

        return $qb->getSingleScalarResult();


    }

    // /**
    //  * @return BookmakerRating[] Returns an array of BookmakerRating objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BookmakerRating
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
