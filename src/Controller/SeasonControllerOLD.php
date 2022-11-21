<?php

// src/Controller/ProgramController.php

namespace App\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use App\Repository\ProgramRepository;
use App\Repository\CategoryRepository;

#[Route('/season', name: 'season_')]
class SeasonControllerOLD extends AbstractController
{

    #[Route('/', name: 'index')]
    public function index(SeasonRepository $seasonRepository, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        $seasons = $seasonRepository->findAll();
        return $this->render('program/index.html.twig', [
            'website' => 'Wild Series',
            'seasons' => $seasons,
            'categories' => $categories
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
