<?php

namespace App\Controller\Other;

use App\Repository\EpisodeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EpisodeController extends AbstractController
{
    #[Route('/episodes', name: 'episode_list')]
    public function list(EpisodeRepository $episodeRepository): Response
    {
        $episodes = $episodeRepository->findAll();

        return $this->render('episode/list.html.twig', [
            'episodes' => $episodes,
        ]);
    }

    #[Route('/episode/{id}', name: 'episode_detail', requirements: ['id' => '\d+'])]
    public function detail(int $id, EpisodeRepository $episodeRepository): Response
    {
        $episode = $episodeRepository->find($id);

        if (!$episode) {
            throw $this->createNotFoundException('Episode not found.');
        }

        return $this->render('episode/detail.html.twig', [
            'episode' => $episode,
        ]);
    }

    #[Route('/season/{seasonId}/episodes', name: 'season_episodes', requirements: ['seasonId' => '\d+'])]
    public function episodesBySeason(int $seasonId, EpisodeRepository $episodeRepository): Response
    {
        $episodes = $episodeRepository->findBy(['season' => $seasonId]);

        return $this->render('episode/season.html.twig', [
            'episodes' => $episodes,
        ]);
    }
}
