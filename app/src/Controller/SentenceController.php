<?php

namespace App\Controller;

use App\Entity\Sentence;
use App\Repository\CommentRepository;
use App\Repository\SentenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function show(Sentence $sentence, CommentRepository $commentRepository): Response
    {   
        $comments = $commentRepository->findBy(
            ['sentence' => $sentence], 
            ['createdAt' => 'DESC']);
        
            return $this->render('sentence/show.html.twig', [
            'sentence' => $sentence,
            'comments' => $comments
        ]);
    }

    #[Route('/sentence/{id}/like', name: 'app_sentence_like')]
    public function like(Sentence $sentence, EntityManagerInterface $entityManager): Response
    {
    $sentence->setLikes($sentence->getLikes() + 1);
    $entityManager->flush();

    return $this->redirectToRoute('app_sentence_show', ['id' => $sentence->getId()]);
    }
}
