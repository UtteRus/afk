<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditUserType;
use App\Form\GetUserRoleType;
use App\Form\SelectCommanderType;
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

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $entityManager->persist($user);
            $entityManager->flush();
        }


        return $this->render('user-edit.html.twig', [
            'user' => $form->createView()

        ]);
    }

    #[Route('/get-role', name: 'getRole')]
    #[IsGranted("ROLE_OFICER")]
    public function getRoleAdmin(EntityManagerInterface $entityManager, Request $request): Response
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if($this->isGranted('ROLE_ADMIN')){
            $users= $entityManager->getRepository(User::class)->findAll();


            $formAdmin=$this->createForm(GetUserRoleType::class, $users);
            $formAdmin->handleRequest($request);

            if($formAdmin->isSubmitted()) {
                $role = $formAdmin['getRole']->getData();
                $user = $formAdmin['email']->getData();

                if ($user->getRoles() != 'ROLE_ADMIN') {
                    $user->setRoles([$role]);
                    $entityManager->persist($user);
                    $entityManager->flush();
                    $this->addFlash('success', 'Роль изменина');
                }
            }
            return $this->render('get-role.html.twig',[
                'user'=>$formAdmin->createView()

            ]);
        } elseif ($this->isGranted('ROLE_OFICER')){
            $users= $entityManager->getRepository(User::class)->findAll();


            $formOfficer=$this->createForm(SelectCommanderType::class, $users);
            $formOfficer->handleRequest($request);

            if($formOfficer->isSubmitted()){
                $role=$formOfficer['getRole']->getData();
                $user=$formOfficer['email']->getData();

                if ($user->getRoles() != 'ROLE_ADMIN' or $user->getRoles() != 'ROLE_OFICER'){
                    $user->setRoles([$role]);
                    $entityManager->persist($user);
                    $entityManager->flush();
                    $this->addFlash('success', 'Роль изменина');
                }
            }
            return $this->render('get-role.html.twig',[
                'user'=>$formOfficer->createView()

            ]);

        }

        return $this->redirectToRoute('hero');
    }
}
