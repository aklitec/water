<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Query;

/**
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Client::class);
    }

    public function findLatestClients(int $page=1):Pagerfanta
    {
        $qb = $this->createQueryBuilder('e')
            ->orderBy('e.id', 'DESC');
        return  $this->createPaginator($qb->getQuery(),$page);

    }

    private function createPaginator(Query $query, int $page): Pagerfanta
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter($query));
        $paginator->setMaxPerPage(Client::NUM_ITEMS);
        $paginator->setCurrentPage($page);
        return $paginator;
    }


    public function  findAllClients(){
        return $this
            ->createQueryBuilder('s')
            ->orderBy('s.id', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function  findMatchedClients($query , int $page=1){
        $qb = $this
            ->createQueryBuilder('c')
            ->orderBy('c.id', 'DESC');
        if (!empty($x = trim($query['cin']))) {
            $qb->orWhere('c.cin LIKE :cin')
                ->setParameter('cin', '%' . $x . '%');
        }
        if (!empty($x = trim($query['fullName']))) {
            $qb->orWhere('c.fullName LIKE :fullName')
                ->setParameter('fullName', '%' . $x . '%');
        }
        if (!empty($x = trim($query['phoneNumber']))) {
            // $qb->andWhere('s.cne = :scne')->setParameter('scne', $x);
            $qb->orWhere('c.email LIKE :phoneNumber')->setParameter('phoneNumber', '%' . $x . '%');
        }
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
