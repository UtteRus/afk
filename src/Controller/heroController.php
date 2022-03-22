<?php

namespace App\Controller;

use App\Entity\Hero;
use App\Entity\Hire;
use App\Entity\Specifications;
use App\Entity\User;
use App\Form\AddHeroType;
use App\Form\EditSpecaficationsUserType;
use App\Form\EditSpecificationsOficerType;
use App\Services\FileUploader;
use App\Services\WorkWithHero;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class heroController extends AbstractController
{
    #[Route('/', name: 'home')]
    function home(): Response
    {
        return $this->render('/homepage.html.twig');
    }



    #[Route('/герои/мои_герои', name: 'hero')]
    #[IsGranted("ROLE_GUILD")]
    function  viewHeroUser(EntityManagerInterface $entityManager) : Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();


        $findUser=$entityManager->getRepository(User::class)->findOneBy(['id'=>$user]);

        $specifications=$entityManager->getRepository(Specifications::class)->findBy(['uid'=>$findUser]);


        return $this->render('/hero.html.twig', [ 'specifications'=>$specifications,

        ]);
    }


    /**
     * @throws \Doctrine\DBAL\Exception
     */
    #[Route('/герои/добавить_героя', name: 'heroAdd')]
    #[IsGranted("ROLE_USER")]
    public function addHero(Request $request, FileUploader $fileUploader,
                            WorkWithHero $workWithHero) :Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $newHero = new Hero;
        $form = $this->createForm(AddHeroType::class, $newHero);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid())
        {
            $workWithHero->addNewHero($form, $newHero, $fileUploader);
            return $this->redirectToRoute('hero');
        }

        return $this->render('/hero-add.html.twig',[
            'form'=>$form->createView()
        ]);

    }



    #[Route('/герои/просмотр-таблицы-игроков', name: 'heroView')]
    #[IsGranted("ROLE_COMMANDER")]
    public function viewUserHero(EntityManagerInterface $entityManager, Request $request) :Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if($this->isGranted('ROLE_OFICER')){
            $findUserAll=$entityManager->getRepository(User::class)->findAll();

            if($request->isMethod('post')){
                $id=$request->get('selectUser');
                $findUser=$entityManager->getRepository(User::class)->findOneBy(['id'=>$id]);
                $specifications=$entityManager->getRepository(Specifications::class)->findBy(['uid'=>$findUser]);

                return $this->render('viewUserHero.html.twig',[
                    'user'=>$findUser, 'myUser'=>$findUserAll,'specifications'=>$specifications ]);
            }
            return $this->render('viewUserHero.html.twig',[
                'myUser'=>$findUserAll
            ]);
        }elseif ($this->isGranted('ROLE_COMMANDER')){
            $myUser=$this->getUser()->getUserIdentifier();
            $myUserName=$entityManager->getRepository(User::class)->findOneBy(['email'=>$myUser]);

            $findMyUser=$entityManager->getRepository(User::class)->findBy(['commander'=>$myUserName->getUserName()]);

            if($request->isMethod('post')){
                $id=$request->get('selectUser');
                $findUser=$entityManager->getRepository(User::class)->findoneBy(['id'=>$id]);
                $specifications=$entityManager->getRepository(Specifications::class)->findBy(['uid'=>$findUser]);

                return $this->render('viewUserHero.html.twig',[
                    'user'=>$findUser, 'myUser'=>$findMyUser,'specifications'=>$specifications ]);
            }
            return $this->render('viewUserHero.html.twig',[
                'myUser'=>$findMyUser
            ]);
        }


        return $this->render('viewUserHero.html.twig', [
        ]);
    }


    #[Route('/герои/{id}/редактирование-героя/{heroName}', name: 'edit-hero')]
    public function editHeroSpecifications(EntityManagerInterface $entityManager, int $id, Request $request, FileUploader $fileUploader,
                                           WorkWithHero $workWithHero): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $specifications =$entityManager->getRepository(Specifications::class)->findOneBy(['id' => $id]);

        $findHire=$entityManager->getRepository(Hire::class)->findHireHero($userName=$specifications->getUid()->getUserName(),
            $heroName =$specifications->getHid()->getHeroName());


        if($this->isGranted('ROLE_OFICER')){
            $form = $this->createForm(EditSpecificationsOficerType::class, $specifications);
            if (isset($findHire)){
                $form->get('hire')->setData(true);
            }

            $form->handleRequest($request);

            if($form ->getClickedButton() === $form->get('save') && $form->isValid())
            {
                $workWithHero->getHireHero($form);

                $workWithHero->editHero($form,$fileUploader, $specifications );

                //если редактировали чужого героя то переходит на его страницу
                $user= $request->query->get('user');
                if ( isset($user)){
                    if($this->isGranted('ROLE_OFICER')){
                        $findUserAll=$entityManager->getRepository(User::class)->findAll();

                        if(isset($user)){
                            $findUser=$entityManager->getRepository(User::class)->findOneBy(['userName'=>$user]);
                            $specifications=$entityManager->getRepository(Specifications::class)->findBy(['uid'=>$findUser]);

                            return $this->render('viewUserHero.html.twig',[
                                'user'=>$findUser, 'myUser'=>$findUserAll,'specifications'=>$specifications ]);
                        }
                        return $this->render('viewUserHero.html.twig',[
                            'myUser'=>$findUserAll
                        ]);
                    }elseif ($this->isGranted('ROLE_COMMANDER')){
                        $myUser=$this->getUser()->getUserIdentifier();
                        $myUserName=$entityManager->getRepository(User::class)->findOneBy(['email'=>$myUser]);

                        $findMyUser=$entityManager->getRepository(User::class)->findBy(['commander'=>$myUserName->getUserName()]);

                        if(isset($user)){
                            $findUser=$entityManager->getRepository(User::class)->findoneBy(['userName'=>$user]);
                            $specifications=$entityManager->getRepository(Specifications::class)->findBy(['uid'=>$findUser]);

                            return $this->render('viewUserHero.html.twig',[
                                'user'=>$findUser, 'myUser'=>$findMyUser,'specifications'=>$specifications ]);
                        }

                        return $this->render('viewUserHero.html.twig',[
                            'myUser'=>$findMyUser
                        ]);
                    }
                    return $this->render('viewUserHero.html.twig',[ 'specifications'=>$specifications]);
                }
                return $this->redirectToRoute('hero' );
            }

            if($form ->getClickedButton() === $form->get('delete') && $form->isValid()){
                $workWithHero->deleteHero($request);
                return $this->redirectToRoute('hero');
            }
            return $this->render('/hero-editOficer.html.twig',[
                'data'=>$specifications,
                'form' => $form->createView(),

            ]);

        }else{
            $form = $this->createForm(EditSpecaficationsUserType::class, $specifications);

            if (isset($findHire)){
                $form->get('hire')->setData(true);
            }

            $form->handleRequest($request);

            if($form ->getClickedButton() === $form->get('save') && $form->isValid())
            {
                $workWithHero->getHireHero($form);

                $specifications=$form->getData();
                $entityManager->persist($specifications);
                $entityManager->flush();
                return $this->redirectToRoute('hero');
            }
        }
        return $this->render('/hero-editUser.html.twig',[
            'data'=>$specifications,
            'form' => $form->createView(),

        ]);
    }

}