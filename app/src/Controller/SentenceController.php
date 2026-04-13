<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Like;
use App\Entity\Sentence;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\LikeRepository;
use App\Repository\SentenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SentenceController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(SentenceRepository $sentenceRepository): Response
    {
        $sentences = $sentenceRepository->findBy([], ['createdAt' => 'DESC']);
        
        return $this->render('home/index.html.twig', [
            'sentences' => $sentences
        ]);
    }

    #[Route('/sentence/{id}', name: 'app_sentence_show')]
    public function show(Sentence $sentence, CommentRepository $commentRepository, Request $request, LikeRepository $likeRepository, EntityManagerInterface $entityManager): Response
    {   
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setAuthor($this->getUser());
            $comment->setCreatedAt(new \DateTimeImmutable());
            $comment->setSentence($sentence);

            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('app_sentence_show', ['id' => $sentence->getId()]);
        }

        $comments = $commentRepository->findBy(
            ['sentence' => $sentence], 
            ['createdAt' => 'DESC']
        );

        $isLiked = $this->getUser() ? $likeRepository->findOneBy([
            'user' => $this->getUser(),
            'sentence' => $sentence,
        ]) : false;
        
        return $this->render('sentence/show.html.twig', [
            'sentence' => $sentence,
            'comments' => $comments,
            'commentForm' => $form,
            'isLiked' => $isLiked,
        ]);
    }

    #[Route('/sentence/{id}/like', name: 'app_sentence_like')]
    public function like(Sentence $sentence, EntityManagerInterface $entityManager, LikeRepository $likeRepository): Response
    {   
        $user = $this->getUser();

        if(!$user) {
            return $this->redirectToRoute('app_login');
        }

        $existingLike = $likeRepository->findOneBy([
            'user' => $user,
            'sentence' => $sentence,
        ]);

        if ($existingLike) {
            $entityManager->remove($existingLike);
            $sentence->setLikes($sentence->getLikes() -1);
        } else {
            
            $like = new Like();
            $like->setUser($user);
            $like->setSentence($sentence);
            $entityManager->persist($like);
            $sentence->setLikes($sentence->getLikes() + 1);

        }

        $entityManager->flush();

        return $this->redirectToRoute('app_sentence_show', ['id' => $sentence->getId()]);
    }
}