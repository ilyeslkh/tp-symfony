<?php

namespace App\Controller;

use App\Repository\WatchHistoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WatchHistoryController extends AbstractController
{
    #[Route('/watch-history', name: 'watch_history_list')]
    public function list(WatchHistoryRepository $watchHistoryRepository): Response
    {
        $histories = $watchHistoryRepository->findAll();

        return $this->render('watch_history/list.html.twig', [
            'histories' => $histories,
        ]);
    }

    #[Route('/user/{userId}/watch-history', name: 'user_watch_history')]
    public function userHistory(int $userId, WatchHistoryRepository $watchHistoryRepository): Response
    {
        $histories = $watchHistoryRepository->findByUser($userId);

        return $this->render('watch_history/user.html.twig', [
            'histories' => $histories,
        ]);
    }

    #[Route('/media/{mediaId}/watch-history', name: 'media_watch_history')]
    public function mediaHistory(int $mediaId, WatchHistoryRepository $watchHistoryRepository): Response
    {
        $histories = $watchHistoryRepository->findByMedia($mediaId);

        return $this->render('watch_history/media.html.twig', [
            'histories' => $histories,
        ]);
    }
}
