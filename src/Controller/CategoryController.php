<?php

// src/Controller/ProgramController.php

namespace App\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use App\Repository\ProgramRepository;
use App\Repository\CategoryRepository;

#[Route('/category', name: 'category_')]
class CategoryController extends AbstractController
{
    public const LIMIT_PROGRAM = 3;
    #[Route('/', name: 'index')]
    public function index(ProgramRepository $programRepository, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        return $this->render('category/index.html.twig', [
            'categories' => $categories
        ]);
    }
/*
    #[Route('/{page}', requirements: ['page'=>'\d+'], name: 'page', methods: ['GET'])]
    public function showPage(int $page, CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();
        return $this->render('program/showPage.html.twig', [
            'page' => $page,
            'categories' => $categories
         ]);
    }
*/
    #[Route('/{link<^[a-z-]+$>}/{limit<^[0-9]{0,}$>}', name: 'show', methods: ['GET'])]
    public function show(string $link, CategoryRepository $categoryRepository, ProgramRepository $programRepository, int $limit = self::LIMIT_PROGRAM)
    {
        $categories = $categoryRepository->findAll();
        $categoryOne = $categoryRepository->findOneByLink($link);
        if (!$categories) {
            throw $this->createNotFoundException(
                'No categories with link : '.$link. '.'
            );
        }
        $programs = $programRepository->findLimitPrograms($link, $limit);

        return $this->render('category/show.html.twig', [
            'categoryOne' => $categoryOne,
            'categories' => $categories,
            'programs' => $programs
         ]);
    }

    #[Route('/{all<.+>}', name: '404')]
    public function new(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        return $this->render('404.html.twig', [
            'categories' => $categories
        ]);
    }
}
