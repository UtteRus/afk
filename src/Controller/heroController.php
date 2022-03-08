<?php

namespace App\Controller;

use App\Entity\Hero;
use App\Entity\Specifications;
use App\Entity\User;
use App\Form\AddHeroType;
use App\Services\FileUploader;
use App\Form\EditSpecificationsType;
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

        $userAll=$entityManager->getRepository(User::class)->findAll();

        $findUser=$entityManager->getRepository(User::class)->findOneBy(['id'=>$user]);
        $specifications=$entityManager->getRepository(Specifications::class)->findBy(['uid'=>$findUser]);


        return $this->render('/hero.html.twig', [ 'specifications'=>$specifications, 'usersAll'=>$userAll,

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
            $sql=$entityManager->getConnection();
            $stringSqlQuery='INSERT INTO afk.specifications (hid_id, uid_id)
                  SELECT :hid, id from afk.user;
                 ';
            $creat=$sql->prepare($stringSqlQuery);
            $creat->executeQuery([':hid'=>$id]);


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
                    'user'=>$findUser, 'usersAll'=>$findUserAll,'specifications'=>$specifications ]);
            }
            return $this->render('viewUserHero.html.twig',[
                'usersAll'=>$findUserAll
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
    public function editHeroSpecifications(EntityManagerInterface $entityManager, int $id, Request $request, FileUploader $fileUploader, string $heroName): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $specifications =$entityManager->getRepository(Specifications::class)->findOneBy(['id' => $id]);

        $form = $this->createForm(EditSpecificationsType::class, $specifications);
        $form->handleRequest($request);

        if($form ->getClickedButton() === $form->get('save') && $form->isValid())
        {
            $file=$form['imageFile']->getData();

            if($file)
            {
                $nameFile = $fileUploader->uploadImageHero($file);
                $specifications->getHid()->setImg($nameFile);
            }

            $specifications=$form->getData();
            $entityManager->persist($specifications);
            $entityManager->flush();
            return $this->redirectToRoute('hero');
        }
        if($form ->getClickedButton() === $form->get('delete') && $form->isValid()){
            $findHero=$entityManager->getRepository(Hero::class)->findOneBy(['heroName'=>$heroName]);
            $entityManager->remove($findHero);
            $entityManager->flush();
            return $this->redirectToRoute('hero');
        }

        return $this->render('/hero-edit.html.twig',[
            'data'=>$specifications,
            'form' => $form->createView(),

        ]);
    }

}