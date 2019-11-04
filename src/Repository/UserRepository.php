<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Query;
/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findLatest(int $page=1):Pagerfanta
    {
        $qb=$this->createQueryBuilder('s')
                 ->orderBy('s.id', 'DESC');

        return $this->createPaginator($qb->getQuery(),$page);
    }

    private function createPaginator(Query $query, int $page): Pagerfanta
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter($query));
        $paginator->setMaxPerPage(User::NUM_ITEMS);
        $paginator->setCurrentPage($page);
        return $paginator;
    }

    public function latestConnected($limit = 10)
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.lastLogin', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findAllUsers()
    {
        return $this
            ->createQueryBuilder('s')
            ->andWhere('s.deleted = :val')
            ->setParameter('val', false)
            ->orderBy('s.id', 'DESC')
            ->setMaxResults(10)
             ->getQuery()
              ->getResult();

    }


    public function findAllMatched($q, int $page = 1) {

        $qb = $this
            ->createQueryBuilder('u')
            ->orderBy('u.id', 'DESC');
        if (!empty($x = trim($q['username']))) {
            $qb->orWhere('u.username LIKE :username')
                ->setParameter('username', '%' . $x . '%');
        }
        if (!empty($x = trim($q['fullName']))) {
            $qb->orWhere('u.fullName LIKE :fullName')
                ->setParameter('fullName', '%' . $x . '%');
        }
        if (!empty($x = trim($q['email']))) {
            // $qb->andWhere('s.cne = :scne')->setParameter('scne', $x);
            $qb->orWhere('u.email LIKE :email')->setParameter('email', '%' . $x . '%');
        }

        return $this->createPaginator($qb->getQuery(), $page);
    }
}
