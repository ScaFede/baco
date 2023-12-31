<?php

namespace App\Controller;

use App\Entity\CompetenzeBis;
use App\Form\CompetenzeBisType;
use App\Repository\CompetenzeBisRepository;
use App\Repository\CittaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
//use App\Form\CompetenzeType;
use App\Form\CategorieType;
use App\Entity\Categorie;
use App\Entity\Citta;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CategorieRepository;



#[Route('/competenze/bis')]
class CompetenzeBisController extends AbstractController
{


  private EntityManagerInterface $entityManager;

  public function __construct(EntityManagerInterface $entityManager)
  {
      $this->entityManager = $entityManager;
  }





    #[Route('/', name: 'app_competenze_bis_index', methods: ['GET'])]
    public function index(CompetenzeBisRepository $competenzeBisRepository, CategorieRepository $categorieRepository,  CittaRepository $cittaRepository, Request $request): Response
    {
        $categories = $categorieRepository->findAll(); // Recupera tutte le categorie
        // Ordina le categorie in ordine alfabetico basato sul campo 'nome'
        usort($categories, function($a, $b) {
            return strcmp($a->getNome(), $b->getNome());
        });

        $selectedCategoryId = $request->query->get('category'); // ID category

        $cities = $cittaRepository->findAll(); // Recupera tutte le città
        // Ordina le città in ordine alfabetico basato sul campo 'nome'
        usort($cities, function($a, $b) {
            return strcmp($a->getNome(), $b->getNome());
        });

        $selectedCityId = $request->query->get('city'); // ID città

        //$searchTerm = $request->query->get('search');
        $searchTerm = $request->query->get('search', ''); // '' assegna stringa vuota se il parametro è null
        dump($searchTerm);

        if ($searchTerm !== '') {
            $competenze_bis = $competenzeBisRepository->findBySearchTerm($searchTerm);
        } elseif ($selectedCategoryId) { // Competenze con filtro per categoria
            $competenze_bis = $competenzeBisRepository->findByCategory($selectedCategoryId);
        } elseif ($selectedCityId) { // Competenze con filtro per città
            $competenze_bis = $competenzeBisRepository->findByCity($selectedCityId);
        }else {
            $competenze_bis = $competenzeBisRepository->findAll();
        }

        return $this->render('competenze_bis/index.html.twig', [
          //  'competenze_bis' => $competenzeBisRepository->findAll(),
            'competenze_bis' => $competenze_bis,
            'categories' => $categories,
            'cities' => $cities,
        ]);
    }

    #[Route('/new', name: 'app_competenze_bis_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CompetenzeBisRepository $competenzeBisRepository): Response
    {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $competenzeBi = new CompetenzeBis();
        $form = $this->createForm(CompetenzeBisType::class, $competenzeBi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

          // Save the changes to the database
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
    public function edit(Request $request, CompetenzeBis $competenzeBi, CompetenzeBisRepository $competenzeBisRepository, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $form = $this->createForm(CompetenzeBisType::class, $competenzeBi);

        // Aggiungi il campo categorieRelation come EntityType nel form
        /*$form->add('categorieRelation', EntityType::class, [
            'class' => Categorie::class,
            'choice_label' => 'nome', // Sostituisci con il campo corretto da visualizzare
            'multiple' => true,
            'expanded' => true,
        ]);*/

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

          // Associare le categorie selezionate alla competenza
        /*  $selectedCategories = $form->get('categorieRelation')->getData();
          foreach ($selectedCategories as $selectedCategory) {
              $competenzeBi->addCategorieRelation($selectedCategory);
          }*/


          $entityManager->persist($competenzeBi);
          $entityManager->flush();

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
        $this->denyAccessUnlessGranted('ROLE_ADMIN');


        if ($this->isCsrfTokenValid('delete'.$competenzeBi->getId(), $request->request->get('_token'))) {
            $competenzeBisRepository->remove($competenzeBi, true);
        }

        return $this->redirectToRoute('app_competenze_bis_index', [], Response::HTTP_SEE_OTHER);
    }
}
