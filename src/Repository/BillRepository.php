<?php

namespace App\Repository;

use App\Entity\Bill;
use App\Entity\WaterMeter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

/**
 * @method Bill|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bill|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bill[]    findAll()
 * @method Bill[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BillRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bill::class);
    }

    public function findAllBills($query , int $page=1):Pagerfanta
    {
        $qb = $this->createQueryBuilder('b');
        $qb->select('b')
            ->andWhere('b.deleted=0')
            ->andwhere('YEAR(b.consumptionDate)= :year')
            ->setParameter('year', $query)
            ->orderBy('b.printDate', 'DESC');

        return  $this->createPaginator($qb->getQuery(),$page);

    }

    private function createPaginator(Query $query, int $page): Pagerfanta
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter($query));
        $paginator->setMaxPerPage(Bill::NUM_ITEMS);
        $paginator->setCurrentPage($page);
        return $paginator;
    }


    public function selectYear() {
        $query = $this->createQueryBuilder('b')
            ->select('b');
        return $query;
    }

    public function getBillsByClient($query,$arg) {
        $bq = $this->createQueryBuilder('b');
            if($arg=='cost'){
                $bq->select('SUM(b.cost) AS totalCost')
                    ->andwhere('b.status=1')
                    ->andWhere('b.client=:query');
            }else{
                $bq->select('SUM(b.consumption) AS totalConsumption')
                    ->andWhere('b.client=:query');
            }
                $bq->setParameter('query',$query);
        return $bq->getQuery()->getSingleScalarResult();
    }

    public function  findMatchedBillMeters($query , int $page=1){
        $qb = $this->createQueryBuilder('b');
        $qb->leftJoin('b.client','c')
            ->select('b');
        if (!empty($x = trim($query['cin']))) {
            $qb->andWhere('c.cin =:cin')
                ->setParameter('cin', $x);
        }
        if (!empty($x = trim($query['code']))) {
            $qb->andWhere('b.code =:code')
                ->setParameter('code', $x);
        }
        if (!empty($x = trim($query['status']))) {
            $qb->andWhere('b.status=:status')
                ->setParameter('status', $x );
        }
        if (!empty($x = trim($query['month']))) {
            $qb->andWhere('MONTH(b.printDate)=:month')
                ->setParameter('month', $x );
        }
        if (!empty($x = trim($query['year']))) {
            $qb->andWhere('MONTH(b.printDate)=:year')
                ->setParameter('year', $x );
        }
        if (!empty($x = trim($query['fullName']))) {
            $qb ->andWhere('b.fullName LIKE :fullName')
                ->setParameter('fullName', '%'.$x.'%' );
        }
        $qb ->andWhere('b.deleted= :false')
            ->setParameter('false',false)
            ->orderBy('b.id', 'DESC');

        return $this->createPaginator($qb->getQuery(),$page);
    }

    // /**
    //  * @return Bill[] Returns an array of Bill objects
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
    public function findOneBySomeField($value): ?Bill
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
