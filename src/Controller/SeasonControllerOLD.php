<?php

// src/Controller/ProgramController.php

namespace App\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use App\Repository\ProgramRepository;

#[Route('/season', name: 'season_')]
class SeasonControllerOLD extends AbstractController
{

    #[Route('/', name: 'index')]
    public function index(SeasonRepository $seasonRepository): Response
    {        
        $seasons = $seasonRepository->findAll();
        return $this->render('program/index.html.twig', [
            'website' => 'Wild Series',
            'seasons' => $seasons
        ]);
    }

    #[Route('/{all<.+>}', name: '404')]
    public function new(): Response
    {        
        return $this->render('404.html.twig');
    }
}
