<?php

namespace App\Controller;


use App\Form\EditUserType;
use App\Repository\UserRepository;
use App\Services\WorkWithUsers;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;


class UserController extends AbstractController
{
    #[Route('/профиль/', name: 'userView')]
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
            $this->addFlash('success', 'Информация изменена');
        }

        return $this->render('user-edit.html.twig', [
            'user' => $form->createView()

        ]);
    }


    #[Route('/get-role', name: 'getRole')]
    #[IsGranted("ROLE_OFICER")]
    public function getRole(Request $request, WorkWithUsers $workWithUsers, UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($this->isGranted('ROLE_OFICER')) {
            $users = $userRepository->findAll();

            if ($request->isMethod('post')) {

                $myRole = $this->getUser()->getRoles();
                $workWithUsers->getRoleUser($request, $myRole);
                return $this->redirectToRoute('getRole');
            }
            return $this->render('get-role.html.twig', [
                'users' => $users,
            ]);
        }
        return $this->redirectToRoute('hero');
    }


    #[Route('/назначить-командира', name: 'selectUserCommander')]
    public function selectUserCommander(Request        $request, WorkWithUsers $workWithUsers,
                                        UserRepository $userRepository): Response
    {
        $findRoleUser = $userRepository->findByRole('GUILD');
        $findRoleCommander = $userRepository->findByRole('COMMANDER');
        $findRoleOficer = $userRepository->findByRole('OFICER');
        $getRoleCommander = array_merge($findRoleCommander, $findRoleOficer);

        if ($request->isMethod('post')) {
            $workWithUsers->getUserCommander($request);
            $this->redirectToRoute('selectUserCommander');
        }

        return $this->render('selectUserCommander.html.twig', [
            'roleUser' => $findRoleUser, 'roleCommander' => $getRoleCommander,
        ]);
    }


}
