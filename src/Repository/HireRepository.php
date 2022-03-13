<?php

namespace App\Repository;

use App\Entity\Hero;
use App\Entity\Hire;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Hire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hire[]    findAll()
 * @method Hire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hire::class);
    }

    public function addHeroToHireGuild(string $userName,string $heroName,string $parametric){
        $entityManager=$this->getEntityManager();
        $uid=$entityManager->getRepository(User::class)->findOneBy(['userName'=>$userName]);
        $hid=$entityManager->getRepository(Hero::class)->findOneBy(['heroName'=>$heroName]);

        $hire = new Hire();
        $uid->getId();
        $hid->getId();
        $hire->setUid($uid);
        $hire->setHid($hid);
        $hire->setPumping($parametric);
        $hire->setHeroForHire(false);
        return $hire;
    }

    // /**
    //  * @return Hire[] Returns an array of Hire objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Hire
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
