<?php

namespace App\Controller;

use App\Entity\Citta;
use App\Form\CittaType;
use App\Repository\CittaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/citta')]
class CittaController extends AbstractController
{


     #[Route('/import-cities', name: 'import_cities')]
    public function importCities(EntityManagerInterface $entityManager): Response
    {
       $csvFile = __DIR__ . '/Codici-statistici-e-denominazioni-al-30_06_2023.csv';

      var_dump($csvFile);
       //die();
    //    $csvFile = '/path/to/your/csv/Codici-statistici-e-denominazioni-al-30_06_2023.csv';

        if (($handle = fopen($csvFile, 'r')) !== false) {

            while (($data = fgetcsv($handle, 0, ';')) !== false) {

        if (isset($data[6])) {

                  // Converti la colonna "DENOMINAZIONE_UNITA'" in UTF-8
                  $cityName = utf8_encode($data[6]);
              //  $cityName = $data[6]; // Colonna "DENOMINAZIONE_UNITA'"

                if (!empty($cityName)) {
                    // Crea un'istanza dell'entità City e assegna il nome
                    $city = new Citta();
                    $city->setNome($cityName);

                    // Persisti l'istanza dell'entità City nel database
                    $entityManager->persist($city);
                }

              } else {
                echo '<pre>';
                var_dump($data);
                echo '</pre>';

}
            }

            fclose($handle);

            // Esegui il flush per effettuare la persistenza nel database
            $entityManager->flush();

            return new Response('Importazione completata');
        } else {
            return new Response('Impossibile aprire il file CSV.');
        }

    }




    #[Route('/', name: 'app_citta_index', methods: ['GET'])]
    public function index(CittaRepository $cittaRepository): Response
    {
        return $this->render('citta/index.html.twig', [
            'cittas' => $cittaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_citta_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $cittum = new Citta();
        $form = $this->createForm(CittaType::class, $cittum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($cittum);
            $entityManager->flush();

            return $this->redirectToRoute('app_citta_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('citta/new.html.twig', [
            'cittum' => $cittum,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_citta_show', methods: ['GET'])]
    public function show(Citta $cittum): Response
    {
        return $this->render('citta/show.html.twig', [
            'cittum' => $cittum,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_citta_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Citta $cittum, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CittaType::class, $cittum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_citta_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('citta/edit.html.twig', [
            'cittum' => $cittum,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_citta_delete', methods: ['POST'])]
    public function delete(Request $request, Citta $cittum, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cittum->getId(), $request->request->get('_token'))) {
            $entityManager->remove($cittum);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_citta_index', [], Response::HTTP_SEE_OTHER);
    }
}
