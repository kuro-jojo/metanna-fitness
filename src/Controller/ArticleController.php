<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    /**
     * IsGranted("ROLE_RESPONSABLE")
     * @Route("/article/list/{id<\d+>}/{article}", name="app_article_list")
     */
    public function listOfArticles(int $id = -1, string $article = "", ArticleRepository $articleRepository, CategoryRepository $categoryRepository): Response
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
            'category' => $category_name,
            'id' => $id
        ]);
    }

    /**
     * 
     * @Route("/article/search/{id<\d+>}", name="app_article_search")
     */

     public function articleSearch(int $id = -1 , Request $request,ArticleRepository $articleRepository,CategoryRepository $categoryRepository) : Response
     {
        $label = $request->query->get('label');

        $articles = $articleRepository->findByLabel($label);
        $categories = $categoryRepository->findAll();
        
        return $this->render('article/index.html.twig', [
            'articles' => $articles,
            'categories' => $categories,
            'id' => $id
        ]);
     }
}
