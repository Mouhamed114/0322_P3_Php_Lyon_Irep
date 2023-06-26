<?php

namespace App\Controller;

use App\Entity\Presentation;
use App\Form\PresentationType;
use App\Repository\PresentationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/presentation')]
#[IsGranted('ROLE_ADMIN')]
class PresentationController extends AbstractController
{
    #[Route('/new', name: 'app_presentation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PresentationRepository $presentRepository): Response
    {
        $presentation = new Presentation();
        $form = $this->createForm(PresentationType::class, $presentation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $presentRepository->save($presentation, true);

            return $this->redirectToRoute('app_presentation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/presentation/new.html.twig', [
            'presentation' => $presentation,
            'form' => $form,
        ]);
    }

    #[Route('/', name: 'app_presentation_index', methods: ['GET'])]
    public function index(PresentationRepository $presentRepository): Response
    {
        return $this->render('admin/presentation/index.html.twig', [
            'presentations' => $presentRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_presentation_show', methods: ['GET'])]
    public function show(Presentation $presentation): Response
    {
        return $this->render('admin/presentation/show.html.twig', [
            'presentation' => $presentation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_presentation_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Presentation $presentation,
        PresentationRepository $presentRepository
    ): Response {
        $form = $this->createForm(PresentationType::class, $presentation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $presentRepository->save($presentation, true);

            return $this->redirectToRoute('app_presentation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/presentation/edit.html.twig', [
            'presentation' => $presentation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_presentation_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Presentation $presentation,
        PresentationRepository $presentRepository
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $presentation->getId(), $request->request->get('_token'))) {
            $presentRepository->remove($presentation, true);
        }

        return $this->redirectToRoute('app_presentation_index', [], Response::HTTP_SEE_OTHER);
    }
}
