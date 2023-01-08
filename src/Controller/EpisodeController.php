<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Comment;
use App\Form\EpisodeType;
use App\Form\CommentType;
use App\Repository\EpisodeRepository;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[Route('/episode')]
class EpisodeController extends AbstractController
{
    #[Route('/', name: 'app_episode_index', methods: ['GET'])]
    public function index(EpisodeRepository $episodeRepository): Response
    {
        return $this->render('episode/index.html.twig', [
            'episodes' => $episodeRepository->findAll()
        ]);
    }

    #[Route('/new', name: 'app_episode_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EpisodeRepository $episodeRepository, SluggerInterface $slugger, MailerInterface $mailer): Response
    {
        $episode = new Episode();
        $form = $this->createForm(EpisodeType::class, $episode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$episode->setTitle();
            $slug = $slugger->slug($episode->getTitle());
            $episode->setSlug($slug);
            $episodeRepository->save($episode, true);
            $mail = $this->getParameter('mailer_from');
            $email = (new Email())
                ->from($mail)
                ->to($mail)
                ->subject('Une nouvelle episode vient d\'être publiée !')
                ->html($this->renderView('Episode/newEpisodeEmail.html.twig', ['episode' => $episode]));
            $mailer->send($email);
            $this->addFlash('success', 'New episode successfully added');
            return $this->redirectToRoute('app_episode_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('episode/new.html.twig', [
            'episode' => $episode,
            'form' => $form
        ]);
    }

    #[Route('/{id}', name: 'app_episode_show', methods: ['GET'])]
    public function show(Episode $episode): Response
    {
        return $this->render('episode/show.html.twig', [
            'episode' => $episode
        ]);
    }

    #[Route('/{id}/edit', name: 'app_episode_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Episode $episode, EpisodeRepository $episodeRepository): Response
    {
        $form = $this->createForm(EpisodeType::class, $episode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $episodeRepository->save($episode, true);
            $this->addFlash('info', 'Episode successfully edited');
            return $this->redirectToRoute('app_episode_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('episode/edit.html.twig', [
            'episode' => $episode,
            'form' => $form
        ]);
    }

    #[Route('/{id}', name: 'app_episode_delete', methods: ['POST'])]
    public function delete(Request $request, Episode $episode, EpisodeRepository $episodeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$episode->getId(), $request->request->get('_token'))) {
            $episodeRepository->remove($episode, true);
        }
        $this->addFlash('danger', 'Episode was deleted');
        return $this->redirectToRoute('app_episode_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/comment_new/{id<[0-9]+$>}', name: 'episode_comment_new')]
    public function newComment(Request $request, CommentRepository $commentRepository, Episode $episode): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $comment->setAuthor($this->getUser());
        $comment->setEpisode($episode);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $commentRepository->save($comment, true);
            return $this->redirectToRoute('app_episode_show', ['id'=> $episode->getId()], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('episode/comment.html.twig', [
            'form' => $form,
            'episode' => $episode
        ]);
    }

    #[Route('/comment_delete/{id<[0-9]+$>}', name: 'episode_comment_delete', methods: ['POST'])]
   // #[Entity('comment', options: ['mapping' => ['id' => 'id']])]
    public function deleteComment(int $id, Request $request, CommentRepository $commentRepository, Comment $comment): Response
    {
        if ($this->getUser() !== $comment->getAuthor() && !(in_array('ROLE_ADMIN', $this->getUser()->getRoles()))) {
            throw $this->createAccessDeniedException('Only the author can delete the comment!');
        }
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $commentRepository->remove($comment, true);
        }
        $this->addFlash('danger', 'Comment was deleted');
        $episode_id = $comment->getEpisode()->getId();
        return $this->redirectToRoute('app_episode_show', ['id'=> $episode_id]);
    }
}
