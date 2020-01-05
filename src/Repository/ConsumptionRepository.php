<?php

namespace App\Repository;

use App\Entity\Consumption;
use App\Entity\WaterMeter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use DoctrineExtensions\Query\Mysql\Year;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method Consumption|null find($id, $lockMode = null, $lockVersion = null)
 * @method Consumption|null findOneBy(array $criteria, array $orderBy = null)
 * @method Consumption[]    findAll()
 * @method Consumption[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConsumptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Consumption::class);
    }


    public function findLatestConsumption($arg)
    {
        $p = $this->createQueryBuilder('c')
            ->leftJoin('c.waterMeter', 'waterMeter')
            ->andWhere('waterMeter.id = :arg')
            ->orderBy('c.date', 'DESC')
            ->setParameter('arg', $arg)
            ->setMaxResults(1)
            ->getQuery();

        try {
            return $p->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
        }


        //  $date= date('Y',strtotime($p->getFirstResult())) +($page-1);

        /*  $cr = $this->createQueryBuilder('e')
                      ->leftJoin('e.waterMeter', 'waterMeter')
                      ->andWhere('waterMeter.id = :arg')
                      ->andWhere('YEAR(e.date) LIKE :date')
                      ->orderBy('e.date', 'ASC')
                      ->setMaxResults(1)
                      ->setParameter('arg',$arg)
                      ->setParameter('date', (string)$date)
                      ->getQuery();
          try {
              return $cr->getOneOrNullResult();
          } catch (NonUniqueResultException $e) {
              return null;
          }*/

    }

    public  function  findAllWaterMetersForBilling($month,$year,$city){
        $qb =$this
            ->createQueryBuilder('c')

            ->andWhere('waterMeter.active=1')
            ->andWhere('waterMeter.deleted=0')
            ->leftJoin('c.waterMeter', 'waterMeter')
            ->leftJoin('waterMeter.client','client')
            ->leftJoin('waterMeter.address','address')
            ->select('c','waterMeter','client')
            ->andWhere('address.city=:city')
            ->andWhere('YEAR(c.date)=:year')
            ->andWhere('MONTH(c.date)=:month')
            ->setParameter('year', $year)
            ->setParameter('month', $month)
            ->setParameter('city', $city);
        return  $qb->getQuery()->getResult();
    }

    public function findConsumptionByEachYear($arg, int $page = 1)//: Pagerfanta
    {
        return $this
            ->createQueryBuilder('s')
            ->select('s, YEAR(s.date) as cYear')
            ->join('s.waterMeter', 'w')
            ->andWhere('w.id = :arg')
            ->groupBy('cYear')
            ->setParameter('arg', $arg)
            ->getQuery()
            ->getResult();
    }

    // 2019 -------------------- 20                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            19 -------------------- 2020


    public function findAllConsumptions($arg,$item)
    {
        $qb = $this
            ->createQueryBuilder('c')
            //->select('c.date')
            ->leftJoin('c.waterMeter', 'waterMeter')
            ->andWhere('YEAR(c.date) LIKE :item')
            ->andWhere('waterMeter.id = :arg')
            ->orderBy('c.date', 'DESC')
            ->setParameter('arg', $arg)
            ->setParameter('item',$item);
        return $qb->getQuery()->getResult();
    }


    private function createPaginator(Query $query, int $page): Pagerfanta
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter($query));
        $paginator->setMaxPerPage(Consumption::NUM_ITEMS);
        $paginator->setCurrentPage($page);
        return $paginator;
    }


    public function selectYear() {
        $query = $this->createQueryBuilder('c')
            ->select('c');
        return $query;
    }

    public function selectMonth() {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $query = $this->createQueryBuilder('c')
            ->select('MONTH(c.date)')
            ->leftJoin('c.waterMeter','waterMeter')
           /* ->Where($queryBuilder->expr()->notIn('c.date','bill.consumption_date'))*/
            ->distinct();

        return $query;
    }

    // /**
    //  * @return Consumption[] Returns an array of Consumption objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Consumption
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
