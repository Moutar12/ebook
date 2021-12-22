<?php

namespace App\Repository;

use App\Entity\Emprunte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Emprunte|null find($id, $lockMode = null, $lockVersion = null)
 * @method Emprunte|null findOneBy(array $criteria, array $orderBy = null)
 * @method Emprunte[]    findAll()
 * @method Emprunte[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmprunteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Emprunte::class);
    }

    // /**
    //  * @return Emprunte[] Returns an array of Emprunte objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Emprunte
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
