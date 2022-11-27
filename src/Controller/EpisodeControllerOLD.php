<?php

// src/Controller/ProgramController.php

namespace App\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use App\Repository\ProgramRepository;

#[Route('/episode', name: 'episode_')]
class EpisodeControllerOLD extends AbstractController
{

    #[Route('/', name: 'index')]
    public function index(EpisodeRepository $seasonRepository): Response
    {        
        $episode = $episodeRepository->findAll();
        return $this->render('program/index.html.twig', [
            'website' => 'Wild Series',
            'episodes' => $episodes
        ]);
    }

    #[Route('/{all<.+>}', name: '404')]
    public function new(): Response
    {        
        return $this->render('404.html.twig');
    }
}
