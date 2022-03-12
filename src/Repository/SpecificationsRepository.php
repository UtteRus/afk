<?php

namespace App\Repository;

use App\Entity\Specifications;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Specifications|null find($id, $lockMode = null, $lockVersion = null)
 * @method Specifications|null findOneBy(array $criteria, array $orderBy = null)
 * @method Specifications[]    findAll()
 * @method Specifications[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpecificationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Specifications::class);
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function addAllUserHero(int $id){

        $entityManager=$this->getEntityManager();
        $sql=$entityManager->getConnection();
        $stringSqlQuery='INSERT INTO afk.specifications (hid_id, uid_id)
                  SELECT :hid, id from afk.user;
                 ';
        $creat=$sql->prepare($stringSqlQuery);
        return $creat->executeQuery([':hid'=>$id]);
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function addAllHeroUser($id){
        $entityManager=$this->getEntityManager();
        $sql=$entityManager->getConnection();
        $stringSqlQuery='INSERT INTO afk.specifications (uid_id, hid_id)
                  SELECT :uid, id from afk.hero;
                 ';
        $creat=$sql->prepare($stringSqlQuery);
        return $creat->executeQuery([':uid'=>$id]);
    }



    // /**
    //  * @return Specifications[] Returns an array of Specifications objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Specifications
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
