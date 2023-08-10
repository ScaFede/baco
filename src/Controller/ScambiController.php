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
    dump('Funziono confirm?');
    // Verifica se la richiesta è una richiesta POST
     if (!$request->isMethod('POST')) {
         throw $this->createAccessDeniedException();
     }

     // Verifica che l'utente autenticato sia uno dei partecipanti allo scambio
     $user = $this->getUser();
     if (!$scambi->getUserSender() === $user && !$scambi->getUserTarget()->contains($user)) {
         throw $this->createAccessDeniedException();
     }

     // Imposta la conferma per l'utente attuale
  /*   if ($scambi->getUserSender() === $user) {
         $scambi->setConfermaSender(true);
     } elseif ($scambi->getUserTarget()->contains($user)) {
         $scambi->setConfermaTarget(true);
     }
*/
     // Verifica se entrambi gli utenti hanno confermato lo scambio
/*   if ($scambi->isConfermaSender() && $scambi->isConfermaTarget()) {
       $scambi->setScambioConfermato(true);
       $scambiRepository->save($scambi);
   }*/


   // Imposta la conferma per l'utente mittente (utente autenticato)
      if ($scambi->getUserSender() === $user) {
          $scambi->setConfermaSender(true);
          $scambiRepository->save($scambi);
      }

    return $this->redirectToRoute('app_scambi_index');
}

#[Route('/{id}/confirm_sender', name: 'app_scambi_confirm_sender', methods: ['GET'])]
public function confirmSender(Scambi $scambio, ScambiRepository $scambiRepository): Response
{
    $user = $this->getUser();

    // Verifica che l'utente autenticato sia il mittente dello scambio
    if ($scambio->getUserSender() === $user) {
        $scambio->setConfermaSender(true);
        $scambiRepository->save($scambio, true); // Salva i cambiamenti nel database

          // Verifica se entrambi confermaSender e confermaTarget sono true
        if ($scambio->isConfermaSender() && $scambio->isConfermaTarget()) {
            $this->incrementScambiConclusi($user); // Incrementa il numero di scambi conclusi per l'utente
        }
    }

    return $this->redirectToRoute('app_scambi_index');
}

#[Route('/{id}/confirm_target', name: 'app_scambi_confirm_target', methods: ['GET'])]
public function confirmTarget(Scambi $scambio, ScambiRepository $scambiRepository): Response
{
    $user = $this->getUser();

    // Verifica che l'utente autenticato sia il destinatario dello scambio
    if ($scambio->getUserTarget()->contains($user)) {
        $scambio->setConfermaTarget(true);
        $scambiRepository->save($scambio, true); // Salva i cambiamenti nel database

        // Verifica se entrambi confermaSender e confermaTarget sono true
        if ($scambio->isConfermaSender() && $scambio->isConfermaTarget()) {
            $this->incrementScambiConclusi($user); // Incrementa il numero di scambi conclusi per l'utente
        }
    }

    return $this->redirectToRoute('app_scambi_index');
}

  private function incrementScambiConclusi(User $user): void
  {
      $scambiConclusi = $user->getScambiConclusi();
      $user->setScambiConclusi($scambiConclusi + 1);
      $this->entityManager->flush();
  }



    #[Route('/', name: 'app_scambi_index', methods: ['GET'])]
    public function index(ScambiRepository $scambiRepository): Response
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

              if ($currentTime > $timeLimit) {
                  $scambio->setStatusString('Scaduto');
              } else {
                  $scambio->setStatusString('Iniziato');
              }
          }
        }





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
    public function new(Request $request, ScambiRepository $scambiRepository, MailerInterface $mailer): Response
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
       // Supponendo che il tuo repository utente si chiami UserRepository

      $userRepository = $this->entityManager->getRepository(User::class);
      $userTarget = $userRepository->findOneBy(['nickname' => $myut]);

       if ($userTarget) {
           $scambi->addUserTarget($userTarget);
       }


      // Imposta il campo statusString in base al tempo di creazione
      $currentTime = new \DateTime();
      $timeLimit = (clone $scambi->getCreatedAt())->modify('+30 days'); // Data di scadenza dopo 30 giorni

     if ($currentTime > $timeLimit) {
         $scambi->setStatusString('Scaduto');
     } else {
         $scambi->setStatusString('Iniziato');
     }



      $form = $this->createForm(ScambiType::class, $scambi);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $scambiRepository->save($scambi, true);


//test invio email
//if ($userTarget) {

    $email = (new Email())
        ->from('federica@brixel.it')
        ->to($userTarget->getEmail())
//        ->to('federica.scalzi@gmail.com')
        ->subject('Hai una nuova richiesta di scambio')
        ->html('La tua richiesta di scambio è stata confermata. Controlla la tua pagina personale per ulteriori dettagli.');

//    $this->mailer->send($email);
    $mailer->send($email);


    dump('Email inviata correttamente'); // Debugging output
//}


            return $this->redirectToRoute('app_scambi_index', [], Response::HTTP_SEE_OTHER);
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
