<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditUserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;


class UserController extends AbstractController
{




    #[Route('/user/', name: 'userView')]
    public function editUser(Request $request, EntityManagerInterface $entityManager): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $user=$form->getData();
            $entityManager->persist($user);
            $entityManager->flush();
        }


        return $this->render('user-edit.html.twig', [
            'user'=>$form->createView()

        ]);
    }

    #[Route('/get-role', name: 'getRole')]
    #[IsGranted("ROLE_OFICER")]
    public function getRoleAdmin(EntityManagerInterface $entityManager, Request $request) : Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if($this->isGranted('ROLE_ADMIN')){
            $users= $entityManager->getRepository(User::class)->findAll();

            if($request->isMethod('post')) {
                $user= $request->get('userName');
                $findUser=$entityManager->getRepository(User::class)->findOneBy(['userName'=>$user]);
                $roles=$findUser->getRoles();
                if((string)array_shift($roles) != 'ROLE_ADMIN'){
                    $role=$request->get('role');
                    $findUser->setRoles([$role]);
                    $findUser->setGuild($request->get('guild'));

                    $entityManager->persist($findUser);
                    $entityManager->flush();
                }
            }

            return $this->render('get-role.html.twig',[
                'users'=> $users

            ]);
        } elseif ($this->isGranted('ROLE_OFICER')){
            $users= $entityManager->getRepository(User::class)->findAll();

            if($request->isMethod('post')) {
                $user= $request->get('userName');
                $findUser=$entityManager->getRepository(User::class)->findOneBy(['userName'=>$user]);
                $roles=$findUser->getRoles();
                if((string)array_shift($roles)!='ROLE_ADMIN' or (string)array_shift($roles)!='ROLE_OFICER'){
                    $role=$request->get('role');
                    $findUser->setRoles([$role]);
                    $findUser->setGuild($request->get('guild'));

                    $entityManager->persist($findUser);
                    $entityManager->flush();
                }
            }

            return $this->render('get-role.html.twig',[
                'users'=> $users
            ]);

        }

        return $this->redirectToRoute('hero');
    }


    #[Route('/назначить-командира', name: 'selectUserCommander')]
    public function selectUserCommander(Request $request, EntityManagerInterface $entityManager) :Response{


        $findRoleUser=$entityManager->getRepository(User::class)->findByRole('USER');
        $findRoleCommander=$entityManager->getRepository(User::class)->findByRole('COMMANDER');



        if($request->isMethod('post')){
            $commander=$request->get('selectCommander');
            $userName=$request->get('userName');

            $findUser=$entityManager->getRepository(User::class)->findOneBy(['userName'=>$userName]);

            $findUser->setCommander($commander);

            $entityManager->persist($findUser);
            $entityManager->flush();
            $this->redirectToRoute('selectUserCommander');
        }

        return $this->render('selectUserCommander.html.twig',[
            'roleUser'=>$findRoleUser, 'roleCommander'=>$findRoleCommander,


        ]);
    }

}
