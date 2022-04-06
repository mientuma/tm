<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    private PaginatorInterface $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Task::class);
        $this->paginator = $paginator;
    }

    public function findAllPaginated($page, $sortMethod): \Knp\Component\Pager\Pagination\PaginationInterface
    {
        $sortMethod = $sortMethod != 'priority' ? $sortMethod : 'ASC';
        $query = $this->createQueryBuilder('t')
            ->orderBy('t.title', $sortMethod)
            ->getQuery();
        $pagination = $this->paginator->paginate($query, $page, 5);
        return $pagination;
    }

    public function findByTitle($query, $page, $sortMethod): \Knp\Component\Pager\Pagination\PaginationInterface
    {
        $sortMethod = $sortMethod != 'priority' ? $sortMethod : 'ASC';
        $qb = $this->createQueryBuilder('t');
        $searchTerms = $this->prepareQuery($query);

        foreach ($searchTerms as $key => $term)
        {
            $qb
                ->orWhere('t.title LIKE :t_'.$key)
                ->setParameter('t_'.$key, '%'.trim($term).'%');
        }

        $dbquery =  $qb
            ->orderBy('t.title', $sortMethod)
            ->getQuery();

        return $this->paginator->paginate($dbquery, $page, 5);

    }

    private function prepareQuery($query): array
    {
        return explode(' ',$query);
    }


    // /**
    //  * @return Task[] Returns an array of Task objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Task
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
