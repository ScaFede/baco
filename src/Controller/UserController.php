<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ScambiRepository;
use Vich\UploaderBundle\Handler\UploadHandler;
use App\Entity\CompetenzeBis;

#[Route('/user')]
class UserController extends AbstractController
{

    private ManagerRegistry $managerRegistry;
    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $managerRegistry, EntityManagerInterface $entityManager)
    {
        $this->managerRegistry = $managerRegistry;
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

  /*  #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }*/

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {



          /** @var UploadedFile $avatar */
          $avatarFile = $form->get('avatar')->getData();

          // this condition is needed because the 'brochure' field is not required
          // so the PDF file must be processed only when a file is uploaded
          if ($avatarFile) {
              $originalFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
              // this is needed to safely include the file name as part of the URL
              //$safeFilename = $slugger->slug($originalFilename);
            //  $newFilename = $safeFilename.'-'.uniqid().'.'.$avatarFile->guessExtension();

              // Move the file to the directory where brochures are stored
              try {
                  $avatarFile->move(
                      $this->getParameter('avatar_directory'),
                      $originalFilename
                  );
              } catch (FileException $e) {
                  // ... handle exception if something happens during file upload
              }

              // updates the 'avatarFilename' property to store the PDF file name
              // instead of its contents
              $user->setAvatar($originalFilename);
          }



          // Rimuovi competenze associate all'utente se presenti nel form
          foreach ($user->getCompetenzeBisRel() as $competenza) {
              $competenza->addUserRelation($user);
          }

          // Rimuovi competenze non associate all'utente se presenti nel form
          $competenzeToRemove = $this->entityManager->getRepository(CompetenzeBis::class)->findAll();
          foreach ($competenzeToRemove as $competenza) {
              if (!$user->getCompetenzeBisRel()->contains($competenza)) {
                  $competenza->removeUserRelation($user);
              }
          }
            $entityManager->persist($user);


            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }


    //from old
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

}
