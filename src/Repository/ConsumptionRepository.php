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


    public function findLatestConsumption($arg,int $page=1)
    {
        $p = $this->createQueryBuilder('e')
            ->select('e.date as y')
            ->orderBy('e.date', 'ASC')
            ->setMaxResults(1)
            ->getQuery();
        ;
        $date= date('Y',strtotime($p->getFirstResult())) +($page-1);

        $cr = $this->createQueryBuilder('e')
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
        }

    }

    public function  findConsumptionByEachYear($arg,int $page=1):Pagerfanta
    {


        $qb =$this
            ->createQueryBuilder('s')
            ->leftJoin('s.waterMeter', 'waterMeter')
            ->andWhere('waterMeter.id = :arg')
            ->orderBy('s.date', 'DESC')
            ->setMaxResults(12)
            ->setParameter('arg',$arg);
        return $this->createPaginator($qb->getQuery(),$page);
    }


    public function  findAllConsumptions($arg)
    {
        $qb =$this
            ->createQueryBuilder('s')
            ->select('s.date')
            ->leftJoin('s.waterMeter', 'waterMeter')
            ->andWhere('waterMeter.id = :arg')
            ->orderBy('s.date', 'DESC')
            ->setMaxResults(12)
            ->setParameter('arg',$arg);
        return  $qb->getQuery()->getResult();
    }


    private function createPaginator(Query $query, int $page): Pagerfanta
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter($query));
        $paginator->setMaxPerPage(Consumption::NUM_ITEMS);
                $paginator->setCurrentPage($page);
        return $paginator;
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
