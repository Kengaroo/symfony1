<?php

// src/Controller/ProgramController.php

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
    public array $categories = [];

    public function __construct(CategoryRepository $categoryRepository) 
    {
        $this->categories = $categoryRepository->findAll();
    }

    #[Route('/', name: 'index')]
    public function index(ProgramRepository $programRepository): Response
    {               
        return $this->render('category/index.html.twig', [
            'categories' => $this->categories
        ]);
    }

    static public function name2link($name)
    {
        $link = mb_strtolower($name, 'utf-8');
        //Pre-processing for French
            $patterns     = ['à', 'â', 'ç', 'è', 'é', 'ê', 'ë', 'î', 'ï', 'ô', 'ù', 'û', 'ü', 'ÿ'];
            $replacements = ['a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'o', 'u', 'u', 'u', 'y'];
            $link         = str_replace($patterns, $replacements, $link);
    
        $patterns     = ["/[^a-z0-9A-Z\s-]/", "/[\s\-\s]/", "/[\s]+/", "/[\s]+/"];
        $replacements = ['', ' ', '-', '-'];
        $link         = trim($link);
        $link         = preg_replace($patterns, $replacements, $link);
        $link         = trim($link, ' -');
    
        return $link;
    }

    #[Route('/{link<^(?!new)[a-z-]+$>}/{limit<^[-9]{0,}$>}', name: 'show', methods: ['GET'])]
    public function show(string $link, CategoryRepository $categoryRepository, ProgramRepository $programRepository, int $limit = self::LIMIT_PROGRAM)
    {        
        $categoryOne = $categoryRepository->findOneByLink($link);
        if (!$categoryOne) {
            throw $this->createNotFoundException(
                'No categories with link : '.$link. '.'
            );
        }
        $programs = $programRepository->findLimitPrograms($link, $limit);

        return $this->render('category/show.html.twig', [
            'categoryOne' => $categoryOne,
            'categories' => $this->categories,
            'programs' => $programs
         ]);
    }

    #[Route('/new', name: 'new')]
    public function new(CategoryRepository $categoryRepository, Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category->setLink(self::name2link($category->getName()));
            $categoryRepository->save($category, true);
            return $this->redirectToRoute('category_index');
        }
        return $this->renderForm('category/new.html.twig', [
            'form' => $form,
            'categories' => $this->categories
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

    #[Route('/{all<.+>}', name: '404')]
    public function notFound(): Response
    {
        return $this->render('404.html.twig', [
            'categories' => $this->categories,
            'goback' => (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/')
        ]);
    }
}
