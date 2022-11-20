<?php

// src/Controller/ProgramController.php

namespace App\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

use App\Repository\ProgramRepository;
use App\Repository\CategoryRepository;
use App\Repository\SeasonRepository;
use App\Form\ProgramType;

use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;

#[Route('/program', name: 'program_')]
class ProgramController extends AbstractController
{
    public const PATH_POSTER = 'assets/images/posters/';

    #[Route('/', name: 'index')]
    public function index(ProgramRepository $programRepository,CategoryRepository $categoryRepository): Response
    {        
        $programs = $programRepository->findAll();
        return $this->render('program/index.html.twig', [
            'website' => 'Wild Series',
            'programs' => $programs,
            'categories' => $categoryRepository->findAll()
        ]);
    }

    #[Route('/{id<^[0-9]+$>}', name: 'show', methods: ['GET'])]
    public function show(Program $program, CategoryRepository $categoryRepository)
    {             
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : '.$id.' found in program\'s table.'
            );
        }
        return $this->render('program/show.html.twig', [
            'program' => $program,
            'categories' => $categoryRepository->findAll()
         ]);
    }

    #[Route('/{program<^[0-9]+$>}/season/{season<^[0-9]+$>}', name: 'season_show', methods: ['GET'])]
    /*#[Entity('program', options: ['id' => 'program_id'])]
    #[Entity('season', options: ['id' => 'season_id'])]*/
    public function showSeason(Program $program, Season $season, CategoryRepository $categoryRepository)
    {        
        if (!$program) {
            throw $this->createNotFoundException(
                'No program found in program\'s table.'//with id : '. $program .' 
            );
        }
        if (empty($season)) {
            throw $this->createNotFoundException(
                'No season  found in season\'s table.'//with id : '. $season->getId() .'
            );
        }
        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'categories' => $categoryRepository->findAll(),
            'season' => $season
         ]);
    }

    #[Route('/{program<^[0-9]+$>}/season/{season<^[0-9]+$>}/episode/{episode<^[0-9]+$>}', name: 'episode_show', methods: ['GET'])]
    public function showEpisode(Program $program, Season $season, Episode $episode, CategoryRepository $categoryRepository)
    {
        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'categories' => $categoryRepository->findAll(),
            'season' => $season,
            'episode' => $episode
         ]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, programRepository $programRepository, CategoryRepository $categoryRepository, SluggerInterface $slugger): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['poster']->getData();
            if ($file) {
                $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $filename;//$slugger->slug($filename);
                $newFilename = $filename. '-'.uniqid() . '.' . $file->guessExtension();                            
                $file->move(self::PATH_POSTER, $newFilename);
                $program->setPoster($newFilename);
            }
            $program->setLink(CategoryController::name2link($program->getTitle()));
            $programRepository->save($program, true);
            return $this->redirectToRoute('program_show', ['id' => $program->getId()]);
        }
        return $this->renderForm('program/new.html.twig', [
            'form' => $form,
            'categories' => $categoryRepository->findAll()
        ]);
    }

    #[Route('/{all<.+>}', name: '404')]
    public function notFound(CategoryRepository $categoryRepository): Response
    {
        return $this->render('404.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'goback' => $_SERVER['HTTP_REFERER']
        ]);
    }
    
}
