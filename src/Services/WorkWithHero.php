<?php

namespace App\Services;

use App\Entity\Hero;
use App\Entity\Hire;
use App\Entity\Specifications;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class WorkWithHero extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Specifications::class);
    }

    public function getHireHero($form)
    {
        $entityManager = $this->getEntityManager();
        $userName = $form->get('userName')->getData();
        $heroName = $form->get('heroName')->getData();
        if ($form->get('hire')->getViewData() == true) {

            $parametric = $form->get('ip')->getData() . ' ' . $form->get('furniture')->getData() . ' ' . $form->get('engraving')->getData();

            $issetHero = $entityManager->getRepository(Hire::class)->findHireHero($userName, $heroName);

            if (!isset($issetHero)) {
                $hire = $entityManager->getRepository(Hire::class)->addHeroToHireGuild($userName, $heroName, $parametric);
                $entityManager->persist($hire);
                $entityManager->flush();
            } else {
                $id = $issetHero->getId();
                $updateHireHero = $entityManager->getRepository(Hire::class)->updateHireHero($id, $parametric);
            }
        } else {
            $issetHero = $entityManager->getRepository(Hire::class)->findHireHero($userName, $heroName);

            if (isset($issetHero)) {

                $entityManager->remove($issetHero);
                $entityManager->flush();
            }
        }
    }

    public function addNewHero($form, $newHero, $fileUploader,)
    {
        $entityManager = $this->getEntityManager();

        $newHero = $form->getData();
        $file = $form['imageFile']->getData();

        if ($file) {
            $nameFile = $fileUploader->uploadImageHero($file);
            $newHero->setImg($nameFile);
        }

        $entityManager->persist($newHero);
        $entityManager->flush();

        $findNewHero = $entityManager->getRepository(Hero::class)->find(['id' => $newHero]);

        (string)$id = current($findNewHero);
        $creatAllUserHero = $entityManager->getRepository(Specifications::class)->addAllUserHero($id);
    }

    public function editHero($form, $fileUploader, $specifications)
    {
        $entityManager = $this->getEntityManager();
        $file = $form['imageFile']->getData();
        if ($file) {
            $nameFile = $fileUploader->uploadImageHero($file);
            $specifications->getHid()->setImg($nameFile);
        }
        $specifications = $form->getData();
        $entityManager->persist($specifications);
        $entityManager->flush();
    }

    public function deleteHero($request)
    {
        $entityManager = $this->getEntityManager();
        $idHero = $request->get('heroId');
        $findHero = $entityManager->getRepository(Hero::class)->find($idHero);

        $entityManager->remove($findHero);
        $entityManager->flush();

    }
}