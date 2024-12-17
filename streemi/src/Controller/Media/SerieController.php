<?php

namespace App\Controller\Media;


use App\Repository\SerieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/media/serie')]
class SerieController extends AbstractController
{

    #[Route('/{id}', name: 'serie_show')]
    public function show(int $id, SerieRepository $serieRepository): Response
    {
        $serie = $serieRepository->find($id);

        if (!$serie) {
            throw $this->createNotFoundException('The serie does not exist');
        }

        return $this->render('media/detail_serie.html.twig', [
            'serie' => $serie,
        ]);
    }
}