<?php

declare(strict_types=1);

namespace App\Controller\Media;

use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/media/film')]
class MovieController extends AbstractController
{

    #[Route('/{id}', name: 'film_show')]
    public function show(int $id, MovieRepository $movieRepository): Response
    {
        $film = $movieRepository->find($id);

        if (!$film) {
            throw $this->createNotFoundException('The film does not exist');
        }

        return $this->render('media/detail.html.twig', [
            'film' => $film,
        ]);
    }
}