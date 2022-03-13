<?php

namespace App\Controller;

use App\Entity\Hero;
use App\Entity\Hire;
use App\Entity\Specifications;
use App\Entity\User;
use App\Form\AddHeroType;
use App\Form\EditSpecificationsUserType;
use App\Form\EditSpecificationsOficerType;
use App\Services\FileUploader;
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
    #[IsGranted("ROLE_USER")]
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
    public function addHero(EntityManagerInterface $entityManager, Request $request, FileUploader $fileUploader) :Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $newHero = new Hero;
        $form = $this->createForm(AddHeroType::class, $newHero);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid())
        {
            $newHero=$form->getData();
            $file=$form['imageFile']->getData();

            if($file)
            {
                $nameFile = $fileUploader->uploadImageHero($file);
                $newHero->setImg($nameFile);
            }


            $entityManager->persist($newHero);
            $entityManager->flush();

            $findNewHero=$entityManager->getRepository(Hero::class)->find(['id'=>$newHero]);

            (string)$id= current($findNewHero);
            $creatAllUserHero=$entityManager->getRepository(Specifications::class)->addAllUserHero($id);


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
    public function editHeroSpecifications(EntityManagerInterface $entityManager, int $id, Request $request, FileUploader $fileUploader): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        //dd($request->query->get('user'));
        $specifications =$entityManager->getRepository(Specifications::class)->findOneBy(['id' => $id]);

        if($this->isGranted('ROLE_OFICER')){
            $form = $this->createForm(EditSpecificationsOficerType::class, $specifications);
            $form->handleRequest($request);

            if($form ->getClickedButton() === $form->get('save') && $form->isValid())
            {
                if ($form->get('hire')->getViewData() == true){

                    $userName=$form->get('userName')->getData();
                    $heroName=$form->get('heroName')->getData();
                    $parametric=$form->get('ip')->getData().$form->get('furniture')->getData().$form->get('engraving')->getData();
                    $hire=$entityManager->getRepository(Hire::class)->addHeroToHireGuild( $userName, $heroName, $parametric);

                    $entityManager->persist($hire);
                    $entityManager->flush();

                }
                $file=$form['imageFile']->getData();

                if($file)
                {
                    $nameFile = $fileUploader->uploadImageHero($file);
                    $specifications->getHid()->setImg($nameFile);
                }

                $specifications=$form->getData();

                $entityManager->persist($specifications);
                $entityManager->flush();
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

                return $this->redirectToRoute('hero', );

            }
            if($form ->getClickedButton() === $form->get('delete') && $form->isValid()){

                $idHero=$request->get('heroId');
                $findHero=$entityManager->getRepository(Hero::class)->find($idHero);

                $entityManager->remove($findHero);
                $entityManager->flush();
                return $this->redirectToRoute('hero');
            }
            return $this->render('/hero-editOficer.html.twig',[
                'data'=>$specifications,
                'form' => $form->createView(),

            ]);

        }else{
            $form = $this->createForm(EditSpecificationsUserType::class, $specifications);

            $form->handleRequest($request);

            if($form ->getClickedButton() === $form->get('save') && $form->isValid())
            {
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