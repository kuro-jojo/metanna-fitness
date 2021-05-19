<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{

    private $articleRepository;
    private $categoryRepository;
    private $paginator;
    private $request;

    public function __construct(ArticleRepository $articleRepository, CategoryRepository $categoryRepository,PaginatorInterface $paginator)
    {
        $this->articleRepository = $articleRepository;
        $this->categoryRepository = $categoryRepository;
        $this->paginator = $paginator;
    }
    /**
     * IsGranted("ROLE_RESPONSABLE")
     * @Route("/article/list/{id<\d+>}/{article}", name="app_article_list")
     */
    public function listOfArticles(int $id = -1, string $article = "",Request $request): Response
    {
        $categories = $this->categoryRepository->findAll();
        $error_category = null;

        $articles = null;
        $category_name = null;
        if ($id == -1) {
            if ($categories[0] != null) {
          
                $articles = $this->paginator->paginate($this->articleRepository->findByCategoryQuery($categories[0]),$request->query->getInt('page',1),9);
                $category_name = $categories[0]->getName();
            } else {
                $error_category = "Aucune catégorie disponible";
            }
        } else {
            if ($this->categoryRepository->find($id) != null) {
                $articles = $this->paginator->paginate($this->articleRepository->findByCategoryQuery($id),$request->query->getInt('page',1),9);
                $category_name = $this->categoryRepository->find($id)->getName();
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
