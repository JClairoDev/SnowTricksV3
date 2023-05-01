<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Repository\CategoryRepository;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(CategoryRepository $categoryRepository, TrickRepository $trickRepository): Response
    {
        $categories=$categoryRepository->findAll();
        $tricks=$trickRepository->findAll();
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
            'categories'=>$categories,
            'tricks'=>$tricks
        ]);
    }

    #[Route('/category/{id}', name: 'app_category_show_trick')]
    public function showTricksByCategory(CategoryRepository $categoryRepository, TrickRepository $trickRepository): Response
    {
        $categories=$categoryRepository->findAll();
        $tricks=$trickRepository->findAll();
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
            'categories'=>$categories,
            'tricks'=>$tricks
        ]);
    }

}
