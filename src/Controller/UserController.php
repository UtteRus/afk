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

    #[Route('/get-role', name: 'getRole', methods: 'GET')]
    #[IsGranted("ROLE_ADMIN")]
    public function getRole(EntityManagerInterface $entityManager, Request $request) : Response
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $users= $entityManager->getRepository(User::class)->findAll();




        return $this->render('get-role.html.twig',[
            'user'=>$users

        ]);
    }
}
