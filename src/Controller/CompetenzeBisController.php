<?php

namespace App\Controller;

use App\Entity\CompetenzeBis;
use App\Form\CompetenzeBisType;
use App\Repository\CompetenzeBisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/competenze/bis')]
class CompetenzeBisController extends AbstractController
{
    #[Route('/', name: 'app_competenze_bis_index', methods: ['GET'])]
    public function index(CompetenzeBisRepository $competenzeBisRepository): Response
    {
        return $this->render('competenze_bis/index.html.twig', [
            'competenze_bis' => $competenzeBisRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_competenze_bis_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CompetenzeBisRepository $competenzeBisRepository): Response
    {
        $competenzeBi = new CompetenzeBis();
        $form = $this->createForm(CompetenzeBisType::class, $competenzeBi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $competenzeBisRepository->save($competenzeBi, true);

            return $this->redirectToRoute('app_competenze_bis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('competenze_bis/new.html.twig', [
            'competenze_bi' => $competenzeBi,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_competenze_bis_show', methods: ['GET'])]
    public function show(CompetenzeBis $competenzeBi): Response
    {
        return $this->render('competenze_bis/show.html.twig', [
            'competenze_bi' => $competenzeBi,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_competenze_bis_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CompetenzeBis $competenzeBi, CompetenzeBisRepository $competenzeBisRepository): Response
    {
        $form = $this->createForm(CompetenzeBisType::class, $competenzeBi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $competenzeBisRepository->save($competenzeBi, true);

            return $this->redirectToRoute('app_competenze_bis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('competenze_bis/edit.html.twig', [
            'competenze_bi' => $competenzeBi,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_competenze_bis_delete', methods: ['POST'])]
    public function delete(Request $request, CompetenzeBis $competenzeBi, CompetenzeBisRepository $competenzeBisRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$competenzeBi->getId(), $request->request->get('_token'))) {
            $competenzeBisRepository->remove($competenzeBi, true);
        }

        return $this->redirectToRoute('app_competenze_bis_index', [], Response::HTTP_SEE_OTHER);
    }
}
