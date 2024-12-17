<?php

namespace App\Controller\Other;

use App\Entity\Movie;
use App\Entity\Serie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\User;

class MediaListController extends AbstractController
{
    #[IsGranted(attribute: "ROLE_USER")]
    #[Route('/my-lists', name: 'media_lists_page')]
    public function getMediaList(Security $security): Response
    {
        $suser = $security->getUser();
        if (!$suser) {
            return $this->redirectToRoute('app_home_page');
        }
        $playlists = $suser->getPlaylist;
        $playlistsData = [];
        foreach ($playlists as $playlist) {
            $filmsCount = 0;
            $seriesCount = 0;

            foreach ($playlist->getPlaylistMedia() as $playlistMedia) {
                $media = $playlistMedia->getMedia();
                if ($media instanceof Movie) {
                    $filmsCount++;
                } elseif ($media instanceof Serie) {
                    $seriesCount++;
                }
            }

            $playlistsData[] = [
                'id' => $playlist->getId(),
                'name' => $playlist->getName(),
                'filmsCount' => $filmsCount,
                'seriesCount' => $seriesCount,
            ];
        }

        return $this->render('other/lists.html.twig', [
            'playlists' => $playlistsData,
        ]);
    }
}