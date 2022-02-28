<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\LoginAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;



class RegistrationController extends AbstractController
{
    /**
     * @throws \Doctrine\DBAL\Exception
     */
    #[Route('/register', name: 'register')]
    public function register(Request                    $request, UserPasswordHasherInterface $userPasswordHasher,
                             UserAuthenticatorInterface $userAuthenticator, LoginAuthenticator $authenticator,
                             EntityManagerInterface     $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            $findUser = $entityManager->getRepository(User::class)->find(['id'=>$user]);

            (string)$id= current($findUser);
            $sql=$entityManager->getConnection();
            $stringSqlQuery='INSERT INTO test.specifications (uid_id, hid_id)
                  SELECT :uid, id from test.hero;
                 ';
            $creat=$sql->prepare($stringSqlQuery);
            $creat->executeQuery([':uid'=>$id]);

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );

        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }


}
