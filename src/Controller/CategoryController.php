<?php

namespace App\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ProgramRepository;
use App\Repository\CategoryRepository;
use App\Form\CategoryType;
use App\Entity\Category;

#[Route('/category', name: 'category_')]
class CategoryController extends AbstractController
{
    public const LIMIT_PROGRAM = 3;

    #[Route('/', name: 'index')]
    public function index(ProgramRepository $programRepository): Response
    {               
        return $this->render('category/index.html.twig');
    }

    #[Route('/{slug<^(?!new)[a-zA-Z0-9-]+$>}/{limit<^[0-9]{0,}$>}', name: 'show', methods: ['GET'])]
    public function show(string $slug, CategoryRepository $categoryRepository, ProgramRepository $programRepository, int $limit = self::LIMIT_PROGRAM)
    {        
        $categoryOne = $categoryRepository->findOneBySlug($slug);
        if (!$categoryOne) {
            throw $this->createNotFoundException(
                'No categories with link : '.$slug. '.'
            );
        }
        $programs = $programRepository->findLimitPrograms($slug, $limit);

        return $this->render('category/show.html.twig', [
            'categoryOne' => $categoryOne,            
            'programs' => $programs
         ]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, CategoryRepository $categoryRepository, SluggerInterface $slugger): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category->setSlug($slugger->slug($category->getName()));
            $categoryRepository->save($category, true);
            return $this->redirectToRoute('category_index');
        }
        return $this->renderForm('category/new.html.twig', [
            'form' => $form            
        ]);
    }
/*
    #[Route('/{page}', requirements: ['page'=>'\d+'], name: 'page', methods: ['GET'])]
    public function showPage(int $page)
    {
        
        return $this->render('program/showPage.html.twig', [
            'page' => $page,
    
         ]);
    }
*/    

    #[Route('/{all<.+>}', name: '404')]
    public function notFound(): Response
    {
        return $this->render('404.html.twig', [            
            'goback' => (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/')
        ]);
    }
}
