<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\MediaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route(path: '/', name: 'page_home')]
    public function accueil(MediaRepository $mediaRepository): Response
    {
        // Récupérer les médias populaires avec une limite
        $popularMedias = $mediaRepository->findPopular(10); // Par exemple, 10 résultats

        // Retourner les médias à la vue
        return $this->render('index.html.twig', [
            'popularMedias' => $popularMedias,
        ]);
    }
}
