<?php 
namespace App\Controller;
use App\Repository\MediaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    #[Route(path:'/home', name: "app_home_page")]
    public function homes(
        MediaRepository $mediaRepository,
    ): Response{
        $medias = $mediaRepository->findPopular(maxResults:9);
        return $this->render('index.html.twig', ['medias' => $medias]);
    }
}