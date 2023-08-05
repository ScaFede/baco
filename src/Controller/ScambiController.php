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

#[Route('/scambi')]
class ScambiController extends AbstractController
{





    #[Route('/', name: 'app_scambi_index', methods: ['GET'])]
    public function index(ScambiRepository $scambiRepository): Response
    {


        return $this->render('scambi/index.html.twig', [
            'scambis' => $scambiRepository->findAll(),
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
    public function new(Request $request, ScambiRepository $scambiRepository): Response
    {
        $scambi = new Scambi();



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
        $scambi->setFromUser($user->getId());

        //https://127.0.0.1:8000/scambi/new?userTarget=utentesic


        /*
        $category = new Categories();
        $categoryDesc = new CategoriesDescription();
        $category->addCategoryDescription($categoryDesc);
        $categoryDesc->setCategory($category);

        */

        $myuserTarget = new User();

        $myut = $request->query->get('userTarget');
        //$myut = $request->request->get('userTarget');


//        $scambi->$myuserTarget->addUserTarget($request->query->get('userTarget'));
        $scambi->$myuserTarget->addUserTarget($myut);
          //$scambi->$myuserTarget->addUserTarget('fedesca');
      //  $scambi->addUserTarget($request->query->get('userTarget'));

      //  $scambi->addUserTarget($myuserTarget);

        $form = $this->createForm(ScambiType::class, $scambi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $scambiRepository->save($scambi, true);

            return $this->redirectToRoute('app_scambi_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('scambi/new.html.twig', [
            'scambi' => $scambi,
            'form' => $form,
            //'setUserTarget' => $scambi->$myut
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
