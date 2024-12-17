<?php

namespace App\Controller\Media;


use App\Repository\MovieRepository;
use App\Repository\WatchHistoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/media/film')]
class MovieController extends AbstractController
{

    #[IsGranted('ROLE_USER')]
    #[Route('/{id}', name: 'movie_show')]
    public function show(int $id, Security $security, MovieRepository $movieRepository, WatchHistoryRepository $watchHistoryRepository): Response
    {
        $movie = $movieRepository->find($id);

        if (!$movie) {
            throw $this->createNotFoundException('The movie does not exist');
        }
        $user = $security->getUser();
        $watchHistory = $watchHistoryRepository->findOneBy(['user' => $user->getId(), 'media' => $id]);
        $nbHoursWatch = round($watchHistory->getNumberOfViews() * $movie->getDuration() / 60);
        return $this->render('movie/detail.html.twig', [
            'movie' => $movie,
            'watchHistory' => $watchHistory,
            'nbHoursWatch' => $nbHoursWatch
        ]);
    }

    
}