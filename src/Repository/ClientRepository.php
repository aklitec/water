<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    public function findLatestClients(int $page=1):Pagerfanta
    {
        $qb = $this->createQueryBuilder('e')
            ->orderBy('e.id', 'DESC');
        $qb->orWhere('e.deleted= :false')
            ->setParameter('false', false);
        return  $this->createPaginator($qb->getQuery(),$page);

    }

    private function createPaginator(Query $query, int $page): Pagerfanta
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter($query));
        $paginator->setMaxPerPage(Client::NUM_ITEMS);
        $paginator->setCurrentPage($page);
        return $paginator;
    }

    public function  findAllClient()
    {
        $qb =$this
            ->createQueryBuilder('s')
            ->orderBy('s', 'DESC')
            ->setMaxResults(25);
        return  $qb->getQuery();
    }


    public function  searchByQuery($query,$max){
        $qb = $this
            ->createQueryBuilder('s');

                $qb->select('s')
                    ->andwhere('s.fullName LIKE :query')
                    ->andWhere('e.deleted= :false')
                    ->setParameter('query', '%'.$query.'%')
                    ->setParameter('false', false)
                    ->setMaxResults($max);

            return $qb->getQuery();
    }

    public function  findMatchedClients($query , int $page=1){
        $qb = $this
            ->createQueryBuilder('c');

        if (!empty($x = trim($query['cin']))) {
            $qb ->andWhere('c.cin = :cin')->setParameter('cin', $x );
        }
        if (!empty($x = trim($query['fullName']))) {
            $qb->andWhere('c.fullName LIKE :fullName')->setParameter('fullName', '%' . $x . '%');
        }
        if (!empty($x = trim($query['phoneNumber']))){
            $qb->andWhere('c.phoneNumber = :phoneNumber')->setParameter('phoneNumber', $x );
        }
            $qb->andWhere('c.deleted= :false')
                ->orderBy('c.id', 'DESC')
                ->setParameter('false', false);
        return $this->createPaginator($qb->getQuery(),$page);
    }

    // /**
    //  * @return Client[] Returns an array of Client objects
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
    public function findOneBySomeField($value): ?Client
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
