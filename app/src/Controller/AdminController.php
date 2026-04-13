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

    #[Route('/admin/add', name: 'app_admin_add')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sentence = new Sentence();
        $form = $this->createForm(SentenceType::class, $sentence);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $sentence->setCreatedAt(new \DateTimeImmutable());
            $sentence->setLikes(0);

            $entityManager->persist($sentence);
            $entityManager->flush();

            $this->addFlash('success', 'Phrase ajoutée avec succès !');
            return $this->redirectToRoute(('app_admin_index'));
        }

        return $this->render('admin/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
