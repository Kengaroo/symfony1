<?php

namespace App\Repository;

use App\Entity\Program;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Program>
 *
 * @method Program|null find($id, $lockMode = null, $lockVersion = null)
 * @method Program|null findOneBy(array $criteria, array $orderBy = null)
 * @method Program[]    findAll()
 * @method Program[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProgramRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Program::class);
    }

    public function save(Program $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Program $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

        /**
     * @return $limit first programs, ordered by $orderBy
     */
    public function findLimitPrograms(string $link, int $limit = 5, string $orderBy = 'id', string $direction = 'DESC'): array
    {
        $entityManager = $this->getEntityManager();

        $query1 = "SELECT p FROM App\Entity\Program p" . " WHERE p.category=(SELECT c.id FROM App\Entity\Category c WHERE c.link=:link) ORDER BY p." . $orderBy . " " . $direction;
        //echo $query1; die;
        $query = $entityManager->createQuery($query1)->setParameter('link', $link);
        if ($limit) {
            $query = $query->setMaxResults($limit);
        }
        // returns an array of Program objects
        return $query->getResult();
    }

//    /**
//     * @return Program[] Returns an array of Program objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Program
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
