<?php

namespace App\Controller;

use App\Entity\Hero;
use App\Entity\Hire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class hireController extends AbstractController
{
    #[Route ('/Найм', name: 'Hire')]
    public function viewHire(EntityManagerInterface $entityManager, Request $request){
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $allHero=$entityManager->getRepository(Hero::class)->findAll();
        $allHire=$entityManager->getRepository(Hire::class)->findAll();
        if($request->isMethod('post')) {
            $heroHire=$entityManager->getRepository(Hire::class)->findBy(['hid'=>$request->get('selectHero')]);

            return $this->render('hire.html.twig',['allHero'=>$allHero, 'heroHire'=>$heroHire, 'heroName'=>$request->get('heroName')]);
        }



        return $this->render('hire.html.twig',['allHero'=>$allHero, 'allHire'=>$allHire]);
    }

}