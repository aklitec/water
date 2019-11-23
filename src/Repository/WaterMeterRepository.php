<?php

namespace App\Repository;

use App\Entity\Client;
use App\Entity\WaterMeter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;


/**
 * @method WaterMeter|null find($id, $lockMode = null, $lockVersion = null)
 * @method WaterMeter|null findOneBy(array $criteria, array $orderBy = null)
 * @method WaterMeter[]    findAll()
 * @method WaterMeter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WaterMeterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WaterMeter::class);
    }

    public function  findAllWaterMetersByClient($args): array
    {
        $qb =$this
            ->createQueryBuilder('wm')
            //->select('wm')
            ->leftJoin('wm.client','client')
            ->andWhere('client.id= :args')
            ->andWhere('wm.deleted=:false')
            ->setParameter('args', $args)
            ->setParameter('false', false)
            ->orderBy('wm.id', 'DESC')
            ->setMaxResults(10);

        return $qb->getQuery()->getResult();
    }



    public function  findAllWaterMeters(int $page=1):Pagerfanta
    {
        $qb =$this
            ->createQueryBuilder('s')
            ->orderBy('s.id', 'DESC')
            ->where('s.deleted= :false')
            ->setParameter('false', false)
            ->setMaxResults(25);
        return  $this->createPaginator($qb->getQuery(),$page);
    }

    public function findLatestWaterMeters(int $page=1):Pagerfanta
    {
        $qb = $this->createQueryBuilder('e')
            ->orderBy('e.id', 'DESC');
        $qb->orWhere('e.active= :value')
            ->setParameter('value', true);
        return  $this->createPaginator($qb->getQuery(),$page);

    }

    private function createPaginator(Query $query, int $page): Pagerfanta
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter($query));
        $paginator->setMaxPerPage(WaterMeter::NUM_ITEMS);
        $paginator->setCurrentPage($page);
        return $paginator;
    }


    public function  findMatchedWaterMeters($query , int $page=1){
            $qb = $this->createQueryBuilder('wm');
            $qb->select('wm')
               ->leftJoin('wm.client','client');

        if (!empty($x = trim($query['cin']))) {
            $qb->andwhere('client.cin = :cin')
                ->setParameter('cin', $x);
        }

        if (!empty($x = trim($query['phoneNumber']))) {
            $qb->andWhere('wm.phoneNumber= :phoneNumber')
                ->setParameter('phoneNumber', $x);
        }
        if (!empty($x = trim($query['wmNumber']))) {
            $qb->andWhere('client.phoneNumber=:phoneNumber')
                ->setParameter('phoneNumber', $x );
        }
        if (!empty($x = trim($query['fullName']))) {
            $qb ->andWhere('client.fullName LIKE :fullName')
                ->setParameter('fullName', '%'.$x.'%' );
        }
            $qb ->andWhere('wm.deleted= :false')
                ->setParameter('false',false)
                ->orderBy('wm.id', 'DESC');

        return $this->createPaginator($qb->getQuery(),$page);
    }

    // /**
    //  * @return WaterMeter[] Returns an array of WaterMeter objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WaterMeter
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
