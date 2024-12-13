<?php

namespace App\Controller;

use App\Repository\MediaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MediaController extends AbstractController
{
    #[Route('/media', name: 'media_list')]
    public function list(MediaRepository $mediaRepository): Response
    {
        $medias = $mediaRepository->findAll();

        return $this->render('media/list.html.twig', [
            'medias' => $medias,
        ]);
    }

    #[Route('/media/{id}', name: 'media_detail', requirements: ['id' => '\d+'])]
    public function detail(int $id, MediaRepository $mediaRepository): Response
    {
        $media = $mediaRepository->find($id);

        if (!$media) {
            throw $this->createNotFoundException('Media not found.');
        }

        return $this->render('media/detail.html.twig', [
            'media' => $media,
        ]);
    }

    #[Route('/media/popular', name: 'media_popular')]
    public function popular(MediaRepository $mediaRepository): Response
    {
        $popularMedias = $mediaRepository->findPopular(10); // 10 médias populaires par défaut

        return $this->render('media/popular.html.twig', [
            'medias' => $popularMedias,
        ]);
    }

    #[Route('/media/search', name: 'media_search')]
    public function search(Request $request, MediaRepository $mediaRepository): Response
    {
        $keyword = $request->query->get('q', ''); // Récupère le mot-clé depuis l'URL
        $medias = $keyword ? $mediaRepository->searchByKeyword($keyword) : [];

        return $this->render('media/search.html.twig', [
            'keyword' => $keyword,
            'medias' => $medias,
        ]);
    }
}
