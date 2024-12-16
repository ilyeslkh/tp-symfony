<?php

declare(strict_types=1);

namespace App\Controller\Movie;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/categories', name: 'category_list')]
    public function listCategories(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('category.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route(path: '/categories/{id}', name: 'category_detail')]
    public function categoryDetail(Category $category): Response
    {
        return $this->render('category/detail.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route(path: '/categorie/{id}', name: 'category_movies')]
    public function getCategoryMovies(int $id, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->find($id);

        if (!$category) {
            throw $this->createNotFoundException('Category not found');
        }

        return $this->render('movie/category.html.twig', ['category' => $category]);
    }

   
}