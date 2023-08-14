<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ScambiRepository;
use Vich\UploaderBundle\Handler\UploadHandler;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/user')]
class OLUserController extends AbstractController
{


    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/user/{username}', name: 'app_user_nickname')]
    public function nickname(string $username, ScambiRepository $scambiRepository): Response

    {
        $userRepository = $this->managerRegistry->getRepository(User::class);

        $user = $userRepository->findOneBy(['nickname' => $username]);

        if (!$user) {
            throw $this->createNotFoundException('Utente non trovato');
        }


        $userCompetenze = $user->getCompetenzeBisRel();   // Recupera le competenze associate all'utente


      //  $proposteInviate = $scambiRepository->findProposteInviate($userID);         // Recupera le proposte di scambio inviate dall'utente corrente

        return $this->render('user/profile.html.twig', [
            'user' => $user,
          //  'proposteInviate' => $proposteInviate,
        ]);
    }


    #[Route('/user/{id}', name: 'app_user_profile')]
    public function profile(int $id, ScambiRepository $scambiRepository): Response
    {
        $userRepository = $this->managerRegistry->getRepository(User::class);
        $user = $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Utente non trovato');
        }

        // Recupera le competenze associate all'utente
        $userCompetenze = $user->getCompetenzeBisRel();

        // Recupera le proposte di scambio inviate dall'utente corrente
        $proposteInviate = $scambiRepository->findProposteInviate($user);

        // Recupera le proposte di scambio ricevute dall'utente corrente
        $proposteRicevute = $scambiRepository->findProposteRicevute($user);

        return $this->render('user/profile.html.twig', [
          'user' => $user,
          'proposteInviate' => $proposteInviate,
          'proposteRicevute' => $proposteRicevute,
        ]);
    }


    #[Route('/user/{id}/edit', name: 'app_user_edit')]
    public function edit(int $id, Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Utente non trovato');
        }

        $editForm = $this->createForm(UserFormType::class, $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_profile', ['id' => $user->getId()]);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'editForm' => $editForm->createView(),
        ]);
    }




}
