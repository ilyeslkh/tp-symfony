<?php

declare(strict_types=1);

namespace App\Controller\Other;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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


    #[Route(path: '/categories{id}', name: 'page_categories')]
    public function categories(String $id,  EntityManagerInterface $entityManager,CategoryRepository $categoryRepository):Response
    {

        dump($id);
        $categories=$categoryRepository ->findAll();
        return $this->render('other/discover.html.twig',['categories'=>$categories]);
    }

    #[Route(path: '/categorie{id}', name: 'page_categories')]
    public function getCategoriesId(String $id,  EntityManagerInterface $entityManager,Category $categorie):Response
    {

        dump($id);
        return $this->render('movie/category.html.twig',['categorie'=>$categorie]);
    }
}