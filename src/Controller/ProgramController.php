<?php

// src/Controller/ProgramController.php

namespace App\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use App\Repository\ProgramRepository;
use App\Repository\CategoryRepository;
use App\Repository\SeasonRepository;

#[Route('/program', name: 'program_')]
class ProgramController extends AbstractController
{
    public const PATH_POSTER = 'assets/images/posters/';

    #[Route('/', name: 'index')]
    public function index(ProgramRepository $programRepository, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        $programs = $programRepository->findAll();
        return $this->render('program/index.html.twig', [
            'website' => 'Wild Series',
            'programs' => $programs,
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
    #[Route('/{id<^[0-9]+$>}', name: 'show', methods: ['GET'])]
    public function show(int $id, ProgramRepository $programRepository, CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();
        $program = $programRepository->findOneBy(['id' => $id]);         
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : '.$id.' found in program\'s table.'
            );
        }
        return $this->render('program/show.html.twig', [
            'program' => $program,
            'categories' => $categories
         ]);
    }

    #[Route('/{programId<^[0-9]+$>}/season/{seasonId<^[0-9]+$>}', name: 'season_show', methods: ['GET'])]
    public function showSeason(int $programId, int $seasonId, ProgramRepository $programRepository, SeasonRepository $seasonRepository, CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();
        $program = $programRepository->findOneBy(['id' => $programId]);   
        $season = $seasonRepository->findOneBy(['id' => $seasonId]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : '. $programId .' found in program\'s table.'
            );
        }
        if (empty($season)) {
            throw $this->createNotFoundException(
                'No season with id : '. $seasonId .' found in season\'s table.'
            );
        }
        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'categories' => $categories,
            'season' => $season
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
