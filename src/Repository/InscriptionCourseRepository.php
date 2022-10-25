<?php

namespace App\Repository;

use App\Entity\InscriptionCourse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InscriptionCourse>
 *
 * @method InscriptionCourse|null find($id, $lockMode = null, $lockVersion = null)
 * @method InscriptionCourse|null findOneBy(array $criteria, array $orderBy = null)
 * @method InscriptionCourse[]    findAll()
 * @method InscriptionCourse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InscriptionCourseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InscriptionCourse::class);
    }

    public function add(InscriptionCourse $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(InscriptionCourse $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return InscriptionCourse[] Returns an array of InscriptionCourse objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?InscriptionCourse
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
