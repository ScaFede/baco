<?php

namespace App\Controller;

use App\Entity\Scambi;
use App\Form\ScambiType;
use App\Repository\ScambiRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Core\User\UserInterface\UserInterface;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Entity\CompetenzeBis;
use App\Repository\CompetenzeBisRepository;


#[Route('/scambi')]
class ScambiController extends AbstractController
{



    private EntityManagerInterface $entityManager;
    private MailerInterface $mailer;

    public function __construct(EntityManagerInterface $entityManager,  MailerInterface $mailer)
    {
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
    }

    #[Route('/{id}/confirm', name: 'app_scambi_confirm', methods: ['POST'])]
    public function confirm(Request $request, Scambi $scambi, ScambiRepository $scambiRepository): Response
{
    // Verifica se la richiesta è una richiesta POST
    /* if (!$request->isMethod('POST')) {
         throw $this->createAccessDeniedException();
     }*/

     // Verifica che l'utente autenticato sia uno dei partecipanti allo scambio
     $user = $this->getUser();
     $userId = $user->getId();
     if (!$scambi->getUserSender() === $user && !$scambi->getUserTarget()->contains($user)) {
         throw $this->createAccessDeniedException();
     }


      if ($scambi && $scambi->getUserSender() === $user) {
       $scambi->setScambioConfermato(true);
       $scambi->setStatusString('Confermato');
       $scambiRepository->save($scambi, true); // Salva i cambiamenti nel database
   }

/*
      if ($request->get('confirm') === 'reject') {
        $scambi->setStatusString('Rifiutato');
        $scambi->setScambioConfermato(false);
        $scambiRepository->save($scambi, true);

}*/
    //  if ($request->request->has('reject')) {
        if ($request->get('confirm') === 'reject') {
           // Aggiorna lo stato a "Rifiutato" nella tua entità PropostaScambio
          $scambi->setStatusString('Rifiutato');
          $scambi->setScambioConfermato(false);
          $scambiRepository->save($scambi, true);
          $this->entityManager->flush();

          dump($request->get('confirm'));
          die();
          //  $this->addFlash('success', 'Scambio rifiutato con successo.');
       }

      return $this->redirectToRoute('app_user_profile', ['id' => $userId, '_fragment' => 'proposte-ricevute']);
    //return $this->redirectToRoute('app_scambi_index');
}

#[Route('/{id}/reject', name: 'app_scambi_reject', methods: ['POST'])]
public function reject(Request $request, Scambi $scambio, ScambiRepository $scambiRepository): Response
{
  /*  if (!$request->isMethod('POST')) {
        throw $this->createAccessDeniedException();
    }

    $user = $this->getUser();
    if (!$scambio->getUserSender() === $user && !$scambio->getUserTarget()->contains($user)) {
        throw $this->createAccessDeniedException();
    }*/

    $scambio->setStatusString('Rifiutato');
    $scambio->setScambioConfermato(false);
    $scambiRepository->save($scambio, true);

    return $this->redirectToRoute('app_scambi_index');
}


#[Route('/{id}/confirm_sender', name: 'app_scambi_confirm_sender', methods: ['GET'])]
public function confirmSender(Scambi $scambio, ScambiRepository $scambiRepository, CompetenzeBisRepository $competenzeRepository): Response
{
    $user = $this->getUser();

    // Verifica che l'utente autenticato sia il mittente dello scambio
    if ($scambio->getUserSender() === $user) {
        $scambio->setConfermaSender(true);

          // Verifica se entrambi confermaSender e confermaTarget sono true
        if ($scambio->isConfermaSender() && $scambio->isConfermaTarget()) {
            $this->incrementScambiConclusi($user, $scambio, $scambiRepository); // Incrementa il numero di scambi conclusi per l'utente
            $scambio->setStatusString('Concluso');
        }

        $scambiRepository->save($scambio, true); // Salva i cambiamenti nel database
        $this->entityManager->flush();



    }
    return $this->redirectToRoute('app_user_profile', ['id' => $user->getId(), '_fragment' => 'scambitab']);

  //  return $this->redirectToRoute('app_scambi_index');
}

#[Route('/{id}/save_competenza', name: 'app_scambi_save_competenza', methods: ['POST'])]
public function saveCompetenza(Request $request, Scambi $scambio, ScambiRepository $scambiRepository, CompetenzeBisRepository $competenzeRepository): Response
{
    $user = $this->getUser();
    $userId = $user->getId();

    //prendi dall'url la competenza selezionata dello user target
   $competenzaId = $request->request->get('competenza');
   $competenzeRepository =  $this->entityManager->getRepository(CompetenzeBis::class);
   $competenzaSender = $competenzeRepository->find(['id' => $competenzaId]);
    if ($competenzaSender) {
         $scambio->setUserSenderCompetenzaRel($competenzaSender);
         $scambio->setStatusString('In attesa');
         $scambiRepository->save($scambio, true);

        $this->entityManager->flush();

        $this->addFlash('proposta_success', 'Proposta avviata con successo!');
    }

  dump($competenzaSender);
//  die();
    //return new Response('Proposta avviata  con successo!');
    //return $this->redirectToRoute('app_scambi_index');
    return $this->redirectToRoute('app_user_profile', ['id' => $userId, '_fragment' => 'proposte-ricevute']);
}



#[Route('/{id}/confirm_target', name: 'app_scambi_confirm_target', methods: ['GET'])]
public function confirmTarget(Scambi $scambio, ScambiRepository $scambiRepository): Response
{
    $user = $this->getUser();

    // Verifica che l'utente autenticato sia il destinatario dello scambio
    if ($scambio->getUserTarget()->contains($user)) {
        $scambio->setConfermaTarget(true);

        // Verifica se entrambi confermaSender e confermaTarget sono true
        if ($scambio->isConfermaSender() && $scambio->isConfermaTarget()) {
            $this->incrementScambiConclusi($user, $scambio, $scambiRepository); // Incrementa il numero di scambi conclusi per l'utente
            $scambio->setStatusString('Concluso');
        }

        $scambiRepository->save($scambio, true); // Salva i cambiamenti nel database
      $this->entityManager->flush();
    }

  //  return $this->redirectToRoute('app_scambi_index');    
    return $this->redirectToRoute('app_user_profile', ['id' => $user->getId(), '_fragment' => 'scambitab']);

}



#[Route('/donazione/{id}', name: 'app_scambi_donazione', methods: ['GET'])]
public function donazioneAction($id, ScambiRepository $scambiRepository, EntityManagerInterface $entityManager)
{

    $user = $this->getUser();
    $userId = $user->getId();
    // Recupera l'oggetto scambio dal repository
    $scambio = $scambiRepository->find($id);

    if (!$scambio) {
        // Gestisci il caso in cui lo scambio non esista
        throw $this->createNotFoundException('Scambio non trovato');
    }

    // Imposta il campo "donazione" su true
    $scambio->setDonazione(true);

    $scambio->setStatusString('Donato');

    // Salva le modifiche nel database
    $entityManager->persist($scambio);
    $entityManager->flush();


    return $this->redirectToRoute('app_user_profile', ['id' => $user->getId(), '_fragment' => 'avviati']);
}






  private function incrementScambiConclusi(User $user, Scambi $scambio, ScambiRepository $scambiRepository): void
  {
      $scambiConclusi = $user->getScambiConclusi();
      $user->setScambiConclusi($scambiConclusi + 1);

      if ($scambio->isConfermaSender() && $scambio->isConfermaTarget()) {
          $scambio->setStatusString('Concluso');
      }

      $this->entityManager->flush();
  }



    #[Route('/', name: 'app_scambi_index', methods: ['GET', 'POST'])]
    public function index(Request $request, ScambiRepository $scambiRepository): Response
    {


        // Recupera l'utente autenticato
        $user = $this->getUser();

        // Recupera gli scambi avviati o ricevuti dall'utente loggato
        $scambi = $scambiRepository->findScambiByUser($user);

        $proposteInviate = $scambiRepository->findProposteInviate($user);
        $proposteRicevute = $scambiRepository->findProposteRicevute($user);


        foreach ($scambi as $scambio) {
          if ($scambio->isConfermaSender() && $scambio->isConfermaTarget()) {
              $scambio->setStatusString('Concluso');

              // Incrementa il numero di scambi conclusi per entrambi gli utenti
              $sender = $scambio->getUserSender();
              $targetUsers = $scambio->getUserTarget();

              if ($sender) {
                  $sender->setScambiConclusi($sender->getScambiConclusi() + 1);
              }

              foreach ($targetUsers as $targetUser) {
                  $targetUser->setScambiConclusi($targetUser->getScambiConclusi() + 1);
              }

          } elseif ($scambio->isConfermaSender() || $scambio->isConfermaTarget()) {
              $scambio->setStatusString('In attesa');
          } elseif (!$scambio->isConfermaSender() && !$scambio->isConfermaTarget()) {
              $currentTime = new \DateTime();
              $timeLimit = (clone $scambio->getCreatedAt())->modify('+60 days');

              //inviare la mail di conferma scambio dopo tot gg

              if ($currentTime > $timeLimit) {
                  $scambio->setStatusString('Scaduto');
              } else {
                  $scambio->setStatusString('Iniziato');
              }
          }
        }



    /*   >>>>>>>>>>>
      if ($request->isMethod('POST')) {
              $confirmTargetId = $request->request->get('confirmTargetId');
              if ($confirmTargetId) {
                  $scambio = $scambiRepository->find($confirmTargetId);
                  if ($scambio && $scambio->getUserTarget()->contains($user)) {
                      $scambio->setScambioConfermato(true);
                      $scambiRepository->save($scambio, true); // Salva i cambiamenti nel database
                  }
              }
          } */






        return $this->render('scambi/index.html.twig', [
            'scambis' => $scambiRepository->findAll(),
            'proposteInviate' => $proposteInviate,
            'proposteRicevute' => $proposteRicevute,
        ]);






    }


    public function indexAction(Request $request)
       {
           // GET http://mysite.com/?user=tim&age=44
           dump($request->query->get('userTarget'));

           return $this->render('::base.html.twig');
       }



    /**
         * @Route("/demo", name="demo")
         */
        #[Route('/chisono', name: 'app_scambi_chisono')]
        public function chisono(Request $request)
        {
            // GET https://127.0.0.1:8000/scambi/chisono?user=chris&age=33
            dump($request->query->get('user'));
            dump($request->query->get('age'));

            return $this->render('scambi/chisono.html.twig');
        }


    #[Route('/new', name: 'app_scambi_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ScambiRepository $scambiRepository, MailerInterface $mailer, CompetenzeBisRepository $competenzeRepository ): Response
    {
        $scambi = new Scambi();

        // Imposta l'utente mittente
       $userSender = $this->getUser();
       $scambi->setUserSender($userSender);




        //Thankssss https://symfony.com/doc/current/security.html#fetching-the-user-object

        // usually you'll want to make sure the user is authenticated first,
        // see "Authorization" below
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // returns your User object, or null if the user is not authenticated
        // use inline documentation to tell your editor your exact User class
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        // Call whatever methods you've added to your User class
        // For example, if you added a getFirstName() method, you can use that.
        //return new Response('Well hi there '.$user->getId());

        //$current_user = $this->getUser()->getId();
      //  $scambi->setFromUser($user->getId());

        //https://127.0.0.1:8000/scambi/new?userTarget=utentesic


        /*
        $category = new Categories();
        $categoryDesc = new CategoriesDescription();
        $category->addCategoryDescription($categoryDesc);
        $categoryDesc->setCategory($category);

        */

       //$myuserTarget = new User();
      $myut = $request->query->get('userTarget');

      $userRepository = $this->entityManager->getRepository(User::class);
      $userTarget = $userRepository->findOneBy(['nickname' => $myut]);

    //  dump($userTarget->getEmail());
    //  die();

       if ($userTarget) {
           $scambi->addUserTarget($userTarget);
       }

       //prendi dall'url la competenza selezionata dello user target
      $competenzaId = $request->query->get('competenza');
      $competenzeRepository =  $this->entityManager->getRepository(CompetenzeBis::class);
      $competenzaTarget = $competenzeRepository->find(['id' => $competenzaId]);
       if ($competenzaTarget) {
            $scambi-> setUserTargetCompetenzaRel($competenzaTarget);
       }


       //  echo 'comeptenza' . dump($competenzaId);
       //  echo '<br>user' . dump($userTarget);
       // die();



      // Imposta il campo statusString in base al tempo di creazione
      $currentTime = new \DateTime();
      $timeLimit = (clone $scambi->getCreatedAt())->modify('+30 days'); // Data di scadenza dopo 30 giorni

     // if ($currentTime > $timeLimit) {
     //     $scambi->setStatusString('Scaduto');
     // } else {
     //     $scambi->setStatusString('Iniziato');
     // }
     $scambi->setStatusString('Iniziato');


      $form = $this->createForm(ScambiType::class, $scambi);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $scambiRepository->save($scambi, true);


//test invio email
//if ($userTarget) {

    $email = (new Email())
        ->from('federica@brixel.it')
        ->to($userTarget->getEmail())
      //  ->to('federica.scalzi@gmail.com')
        ->subject('Hai una nuova richiesta di scambio')
        ->html('La tua richiesta di scambio è stata confermata. Controlla la tua pagina personale per ulteriori dettagli.');

//    $this->mailer->send($email);
    $mailer->send($email);
  //  dump($userTarget->getEmail());

    dump('Email inviata correttamente'); // Debugging output
//}

      $this->addFlash('scambio_start', 'Scambio avviato con successo!');

      // Reindirizza alla pagina dello user dopo aver compilato il form
      return $this->redirectToRoute('app_user_profile', ['id' => $user->getId(), '_fragment' => 'avviati']);
          //  return $this->redirectToRoute('app_scambi_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('scambi/new.html.twig', [
            'scambi' => $scambi,
            'form' => $form,
            'userTarget' => $userTarget
        ]);
    }

    #[Route('/{id}', name: 'app_scambi_show', methods: ['GET'])]
    public function show(Scambi $scambi): Response
    {
        return $this->render('scambi/show.html.twig', [
            'scambi' => $scambi,
        ]);
    }


  /*  #[Route('/chisono', name: 'app_scambi_chisono', methods: ['GET'])]
    public function chisono(ScambiRepository $scambiRepository): Response
    {
        return $this->render('scambi/chisono.html.twig', [
          'scambi' => 25,
          // $current_user = $this->getUser(),
          // $scambi = new Scambi(),
          // $scambi->setFromUser($current_user)
        ]);


    }
*/


    #[Route('/{id}/edit', name: 'app_scambi_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Scambi $scambi, ScambiRepository $scambiRepository): Response
    {
        $form = $this->createForm(ScambiType::class, $scambi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $scambiRepository->save($scambi, true);

            return $this->redirectToRoute('app_scambi_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('scambi/edit.html.twig', [
            'scambi' => $scambi,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_scambi_delete', methods: ['POST'])]
    public function delete(Request $request, Scambi $scambi, ScambiRepository $scambiRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$scambi->getId(), $request->request->get('_token'))) {
            $scambiRepository->remove($scambi, true);
        }

        return $this->redirectToRoute('app_scambi_index', [], Response::HTTP_SEE_OTHER);
    }
}
