<?php

// src/Controller/ProgramController.php

namespace App\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use App\Repository\ProgramRepository;
use App\Repository\CategoryRepository;

#[Route('/episode', name: 'episode_')]
class EpisodeController extends AbstractController
{

    #[Route('/', name: 'index')]
    public function index(EpisodeRepository $seasonRepository, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        $episode = $episodeRepository->findAll();
        return $this->render('program/index.html.twig', [
            'website' => 'Wild Series',
            'episodes' => $episodes,
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
