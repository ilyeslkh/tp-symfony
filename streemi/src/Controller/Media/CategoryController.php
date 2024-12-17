<?php

declare(strict_types=1);

namespace App\Controller\Media;
use App\Entity\Category;
use App\Entity\Movie;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    const MAX_MOVIES = 10;
    #[Route(path: '/category/{id}', name: 'page_category')]
    public function category(
        string $id,
        CategoryRepository $categoryRepository
    ): Response {
        $category = $categoryRepository->find($id);
        $allCat = $categoryRepository->findAll();
        $medias = $category->getMedia();
        $movies = [];
        foreach ($medias as $media) {
            if ($media instanceof Movie) {
                $movies[] = $media;
            }
        }
        $movies = array_slice($movies, 0, self::MAX_MOVIES);

        return $this->render('movie/category.html.twig', [
            'category' => $category,
            'allCat' => $allCat,
            'movies' => $movies
        ]);
    }

    #[Route(path: '/category/{id}/load-more', name: 'load_more_movies')]
    public function loadMoreMovies(
        string $id,
        Request $request,
        CategoryRepository $categoryRepository
    ): Response {
        $offset = $request->query->getInt('offset', 0);
        $category = $categoryRepository->find($id);
        $medias = $category->getMedia();
        $movies = [];
        foreach ($medias as $media) {
            if ($media instanceof Movie) {
                $movies[] = $media;
            }
        }
        $movies = array_slice($movies, $offset, 25); // Charger 25 films supplémentaires

        return $this->render('movie/_movies.html.twig', [
            'movies' => $movies
        ]);
    }
    // autre facon:
    // #[Route(path: '/category/{id}', name: 'page_category')]
    // public function category(
    //     Category $category
    //     ): Response    
    // {
    //     return $this->render('movie/category.html.twig',['category'=> $category]);
    // }

    #[Route(path: '/discover', name: 'page_discover')]
    public function discover(EntityManagerInterface $em, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        return $this->render('movie/discover.html.twig', ['categories' => $categories]);
    }


}
   
