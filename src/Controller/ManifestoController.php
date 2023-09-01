<?php
// src/Controller/ManifestoController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ManifestoController extends AbstractController
{
    #[Route('/manifesto', name: 'app_manifesto')]
    public function faq(): Response
    {
        return $this->render('manifesto/index.html.twig');
    }
}

?>
