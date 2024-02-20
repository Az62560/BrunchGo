<?php

namespace App\Repository;

use App\Entity\DeliveryCities;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DeliveryCities>
 *
 * @method DeliveryCities|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeliveryCities|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeliveryCities[]    findAll()
 * @method DeliveryCities[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeliveryCitiesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DeliveryCities::class);
    }

//    /**
//     * @return DeliveryCities[] Returns an array of DeliveryCities objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DeliveryCities
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
