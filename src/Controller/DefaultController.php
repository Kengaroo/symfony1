<?php

// src/Controller/ProgramController.php

namespace App\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use App\Repository\CategoryRepository;
use ProgramController;

class DefaultController extends AbstractController

{
    #[Route('/', name: 'app_index')]
    public function index(CategoryRepository $categoryRepository): Response

    {
        $categories = $categoryRepository->findAll();
        return $this->render('/index.html.twig', [
            'website' => ' a Wild Series',
            'categories' => $categories
         ]);
    }

}
