<?php

namespace App\Controller;

use App\Entity\Sale;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\SaleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @IsGranted("ROLE_RESPONSABLE")
 * @Route("/article",name="app_article")
 */
class ArticleController extends AbstractController
{

    private $articleRepository;
    private $categoryRepository;
    private $paginator;

    public function __construct(ArticleRepository $articleRepository, CategoryRepository $categoryRepository, PaginatorInterface $paginator)
    {
        $this->articleRepository = $articleRepository;
        $this->categoryRepository = $categoryRepository;
        $this->paginator = $paginator;
    }
    /**
     * @Route("/list/{id<\d+>}/{article}", name="_list")
     * return list Of Articles depends on the category
     *
     * @param  mixed $id
     * @param  mixed $article
     * @param  mixed $request
     * @return Response
     */
    public function listOfArticles(int $id = -1, string $article = "", Request $request): Response
    {
        $categories = $this->categoryRepository->findAll();
        $error_category = null;

        $articles = null;
        $category_name = null;
        if ($id == -1) {
            if ($categories[0] != null) {

                $articles = $this->paginator->paginate($this->articleRepository->findByCategoryQuery($categories[0]), $request->query->getInt('page', 1), 9);
                $category_name = $categories[0]->getName();
            } else {
                $error_category = "Aucune catégorie disponible";
            }
        } else {
            if ($this->categoryRepository->find($id) != null) {
                $articles = $this->paginator->paginate($this->articleRepository->findByCategoryQuery($id), $request->query->getInt('page', 1), 9);
                $category_name = $this->categoryRepository->find($id)->getName();
            } else {
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
     * @Route("/search/{id<\d+>}", name="_search")
     * articleSearch
     *
     * @param  mixed $id
     * @param  mixed $request
     * @param  mixed $articleRepository
     * @param  mixed $categoryRepository
     * @return Response
     */
    public function articleSearch(int $id = -1, Request $request): Response
    {
        $label = $request->query->get('label');

        $articles = $this->paginator->paginate($this->articleRepository->findByLabel($label), $request->query->getInt('page', 1), 9);
        $categories = $this->categoryRepository->findAll();
        return $this->render('article/index.html.twig', [
            'articles' => $articles,
            'categories' => $categories,
            'id' => $id
        ]);
    }
    /**
     * @IsGranted("ROLE_RESPONSABLE")
     * @Route("/add/{id<\d+>}" , name="_add")
     */
    public function addArticle(Article $article, Request $request, SessionInterface $session, FlashyNotifier $flashy, EntityManagerInterface $em): Response
    {
        // First way to treat the sale : sell each article separately (wihtout session)

        // Get the product quantity
        $quantity = $request->query->get('quantity');
        $stock = $article->getStock();
        
        //update the stock
        if ($quantity != null) {
            if ($quantity > $stock || $quantity < 0) {
                $flashy->warning("Quantité incorrecte!");
            } else {
                $article->setStock($stock - $quantity);
                $sale = new Sale;
                $sale->setQuantitySold($quantity);
                $sale->setArticleSold($article);
                $sale->setDateOfSale(new \DateTime);
                $sale->setPreviousStock($stock);
                $sale->setResponsableOfSale($this->getUser());
                
                $em->persist($sale);
                $em->flush();

                $flashy->info("Produit vendu!!");
            }
        }

        return $this->redirectToRoute('app_article_list');
    }

    
    
    /**
     * @Route("/history",name="_history")
     * 
     * history of all sales by responsable
     *
     * @param  mixed $saleRepository
     * @return Response
     */
    public function historyOfsales(SaleRepository $saleRepository) : Response{

        $sales = $saleRepository->findAll();

        return $this->render('article/history.html.twig',[
            'sales' => $sales
        ]);
    }
}
