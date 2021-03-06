<?php

namespace App\Repository;

use App\Entity\Operation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use DateTime;

/**
 * @method Operation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Operation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Operation[]    findAll()
 * @method Operation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OperationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Operation::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Operation $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Operation $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param array $filter
     * @return float|int|mixed|string
     */
    public function findByDateCreateOrLocal(array $filter)
    {
        $qb = $this->createQueryBuilder('o');

        $hasFilter = false;
        if($filter['from'] instanceof DateTime) {
            $qb
                ->where ($qb->expr()->gte('o.dateCreate',':dateFrom'))
                ->setParameter('dateFrom', $filter['from'])
            ;
            $hasFilter = true;
        }

        if($filter['to'] instanceof DateTime) {
            if($hasFilter === true) {
                $qb
                    ->andWhere ($qb->expr()->lte('o.dateCreate',':dateTo'))
                    ->setParameter('dateTo', $filter['to'])
                ;
            } else {
                $qb
                    ->where ($qb->expr()->lte('o.dateCreate',':dateTo'))
                    ->setParameter('dateTo', $filter['to'])
                ;
                $hasFilter = true;
            }
        }

        if($filter['local'] !== '') {
            if($hasFilter === true) {
                $qb
                    ->andWhere ($qb->expr()->eq('o.local',':local'))
                    ->setParameter('local', $filter['local'])
                ;
            } else {
                $qb
                    ->where ($qb->expr()->eq('o.local',':local'))
                    ->setParameter('local', $filter['local'])
                ;
            }
        }

        return $qb->getQuery()->getResult();
    }

    public function getLocals()
    {
        $qb = $this->createQueryBuilder('o');
        $qb
            ->select('o.local')
            ->distinct()
            ->orderBy('o.local')
        ;
        $res = [];
        foreach ($qb->getQuery()->getArrayResult() as $item) {
            $res[] = $item['local'];
        }

        return $res;
    }

    // /**
    //  * @return Operation[] Returns an array of Operation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Operation
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
