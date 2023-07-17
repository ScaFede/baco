<?php

namespace App\Controller;

use App\Entity\Competenze;
use App\Form\CompetenzeType;
use App\Repository\CompetenzeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/competenze')]
class CompetenzeController extends AbstractController
{
    #[Route('/', name: 'app_competenze_index', methods: ['GET'])]
    public function index(CompetenzeRepository $competenzeRepository): Response
    {
        return $this->render('competenze/index.html.twig', [
            'competenzes' => $competenzeRepository->findAll(),
        ]);
    }


}
