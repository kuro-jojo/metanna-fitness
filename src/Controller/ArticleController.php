<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    /**
     * IsGranted("ROLE_RESPONSABLE")
     * @Route("/article/list/{id<\d+>}", name="app_article_list")
     */
    public function listOfArticles(int $id = -1, ArticleRepository $articleRepository, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        $error_category = null;
        $articles = null;
        $category_name = null;
        if ($id == -1) {
            if ($categories[0] != null) {
                $articles = $articleRepository->findByCategory($categories[0]);
                $category_name = $categories[0]->getName();
            } else {
                $error_category = "Aucune catégorie disponible";
            }
        } else {
            if ($categoryRepository->find($id) != null) {
                $articles = $articleRepository->findByCategory($id);
                $category_name = $categoryRepository->find($id)->getName();
            }else {
                return $this->redirectToRoute('app_article_list');
                
            }
        }
        return $this->render('article/index.html.twig', [
            'articles' => $articles,
            'categories' => $categories,
            'category' => $category_name
        ]);
    }
}
