<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_hompage')]
    public function index(CategoryRepository $categoryRepository, TrickRepository $trickRepository): Response
    {
        $categories= $categoryRepository->findAll();
        $tricks=$trickRepository->findAll();

        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'categories'=>$categories,
            'tricks'=>$tricks
        ]);
    }
}
