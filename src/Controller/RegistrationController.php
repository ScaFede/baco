<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Vich\UploaderBundle\Handler\UploadHandler;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\CompetenzeBis;
use App\Entity\UserConoscenzeImage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class RegistrationController extends AbstractController
{




    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, UploadHandler $uploadHandler): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );



            // Handle avatar upload
          /*  $uploadHandler = $this->get('vich_uploader.upload_handler');
           if ($user->getAvatar()) {
               $uploadHandler->upload($user, 'avatar');
           }*/


          /** @var UploadedFile $avatarFile */
          $avatarFile = $form->get('avatar')->getData();


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


          //uploads images competenze
      $uploadedImages = $form['conoscenzeImages']->getData();


            foreach ($uploadedImages as $uploadedImage) {
              if ($uploadedImage) {
                  $conoscenzeImage = new UserConoscenzeImage();
                  $conoscenzeImage->setUser($user);

                  $originalFilename = pathinfo($uploadedImage->getClientOriginalName(), PATHINFO_FILENAME);
                //  $newFilename = $originalFilename.'-'.uniqid().'.'.$uploadedImage->guessExtension();

                  try {
                      $uploadedImage->move(
                          $this->getParameter('conoscenze_images_directory'),
                          $originalFilename
                          //$newFilename
                      );
                  } catch (FileException $e) {
                      // Handle exception
                  }

                  $conoscenzeImage->setImageName($originalFilename);

                  $entityManager->persist($conoscenzeImage);
              }
          }



          //CompetenzeBis Rel
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




          /*  $loginLink = $this->generateUrl('app_login');

            $this->addFlash('success', sprintf('La registrazione è stata completata con successo. Fai il login con l\'email inserita in fase di registrazione
            e inizia a scambiare nella BaCo Community!', $loginLink));
            return $this->redirectToRoute('app_competenze_bis_index'); // Reindirizza all'elenco delle competenze */





            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email


            $this->addFlash('success', sprintf('La registrazione è stata completata con successo. Fai il login con l\'email inserita in fase di registrazione
            e inizia a scambiare nella BaCo Community!'));
            return $this->redirectToRoute('app_competenze_bis_index');
            //return $this->redirectToRoute('admin');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
