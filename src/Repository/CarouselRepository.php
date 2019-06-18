<?php

namespace App\Repository;

use App\Entity\Carousel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Carousel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Carousel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Carousel[]    findAll()
 * @method Carousel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarouselRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Carousel::class);
    }

    public function clearTable() : void {
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'TRUNCATE `symfony`.`carousel`';

        $conn->prepare($sql)->execute();
    }
}
