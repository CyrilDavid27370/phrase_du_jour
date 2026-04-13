<?php

namespace App\Controller;

use App\Entity\Sentence;
use App\Form\SentenceType;
use App\Repository\SentenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
final class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin_index')]
    public function index(SentenceRepository $sentenceRepository): Response
    {
        $sentences = $sentenceRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('admin/index.html.twig', [
            'sentences' => $sentences
        ]);
    }

    #[Route('/admin/save/{id}', name: 'app_admin_save', defaults: ['id' => null])]
    public function save(Request $request, EntityManagerInterface $entityManager, ?Sentence $sentence = null): Response
    {

        if (!$sentence) {
            $sentence = new Sentence();
        }

        $form = $this->createForm(SentenceType::class, $sentence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
    $isNew = !$sentence->getId();

    if ($isNew) {
        $sentence->setCreatedAt(new \DateTimeImmutable());
        $sentence->setLikes(0);
    }

    $entityManager->persist($sentence);
    $entityManager->flush();

    $this->addFlash('success', $isNew ? 'Phrase ajoutée avec succès !' : 'Phrase modifiée avec succès !');
        return $this->redirectToRoute('app_admin_index');
        }
        
        return $this->render('admin/save.html.twig', [
            'form' => $form->createView(),
            'sentence' => $sentence
        ]);
    }

    #[Route('/admin/delete/{id}', name: 'app_admin_delete', methods: ['POST'])]
    public function delete(EntityManagerInterface $entityManager, Sentence $sentence, Request $request): Response
    {
    if ($this->isCsrfTokenValid('delete'.$sentence->getId(), $request->request->get('_token'))) {
        $entityManager->remove($sentence); // "remote" → "remove"
        $entityManager->flush();
        $this->addFlash('success', 'Phrase supprimée avec succès !'); // double é supprimé
    }

    return $this->redirectToRoute('app_admin_index');
    
    }
}