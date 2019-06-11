<?php

namespace App\Repository;

use App\Entity\Bookmaker;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Bookmaker|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bookmaker|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bookmaker[]    findAll()
 * @method Bookmaker[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookmakerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Bookmaker::class);
    }

    public function findAllByGame($game){

      $query =  $this
            ->createQueryBuilder('p')
            ->where('p.games LIKE :game')
            ->setParameter('game', '%' . $game . '%')
            ->getQuery();

      return $query->execute();

    }
}
