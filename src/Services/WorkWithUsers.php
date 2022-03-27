<?php

namespace App\Services;


use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class WorkWithUsers extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }


    public function getRoleUser($request, $myRole)
    {
        $entityManager = $this->getEntityManager();
        $id = $request->get('userId');
        $findUser = $entityManager->getRepository(User::class)->find($id);
        $roles = $findUser->getRoles();
        $role = $request->get('role');

        } elseif ($request->get('delete')) {
            if ((string)current($roles) != 'ROLE_OFICER' && (string)current($roles) != 'ROLE_ADMIN') {
                $entityManager->remove($findUser);
                $entityManager->flush();
            }
        }
    }


    public function getUserCommander($request)
    {
        $entityManager = $this->getEntityManager();

        $commander = $request->get('selectCommander');
        $userName = $request->get('userName');

        $findUser = $entityManager->getRepository(User::class)->findOneBy(['userName' => $userName]);

        $findUser->setCommander($commander);

        $entityManager->persist($findUser);
        $entityManager->flush();
    }


}