<?php

namespace App\Controller;

use App\Entity\Sale;
use App\Entity\Article;
use App\Form\ArticleType;
use App\Service\FileUploader;
use App\Repository\SaleRepository;
use Flasher\Prime\FlasherInterface;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\ResponsableActivityTracker;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/article', name: 'app_article')]

class ArticleController extends AbstractController
{

    private  const  ARTICLE_SELL_ACTIVITY = "Vente du produit \"";
    private  const  ARTICLE_ADD_ACTIVITY = "Ajout du produit \"";
    private  const  ARTICLE_EDIT_ACTIVITY = "Edition du produit \"";
    private  const  ARTICLE_DELETE_ACTIVITY = "Suppression du produit \"";


    private $articleRepository;
    private $categoryRepository;
    private $paginator;
    private $em;
    private $flasher;
    private $responsableTracker;

    public function __construct(ArticleRepository $articleRepository, CategoryRepository $categoryRepository, PaginatorInterface $paginator, EntityManagerInterface $em, FlasherInterface $flasher, ResponsableActivityTracker $responsableTracker)
    {
        $this->articleRepository = $articleRepository;
        $this->categoryRepository = $categoryRepository;
        $this->paginator = $paginator;
        $this->em = $em;
        $this->flasher = $flasher;
        $this->responsableTracker = $responsableTracker;
    }

    #[Route('/list/{id<\d+>}/{article}', name: '_list')]
    /**
     * 
     * @Security("is_granted('ROLE_RIGHT_SELL_ARTICLE') or is_granted('ROLE_ADMIN')")
     * 
     * return list Of Articles depends on the category
     *
     * @param  mixed $id
     * @param  mixed $article
     * @param  mixed $request
     * @return Response
     */
    public function listOfArticles(Request $request, int $id = -1, string $article = ""): Response
    {
        $categories = $this->categoryRepository->findAll();
        $error_category = null;

        $articles = null;
        $category_name = null;
        if ($id == -1) {
            if ($categories != null && $categories[0] != null) {

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

    #[Route('/search/{id<\d+>}', name: '_search')]
    /**
     * 
     * @Security("is_granted('ROLE_RIGHT_SELL_ARTICLE') or is_granted('ROLE_ADMIN')")
     * 
     * articleSearch
     *
     * @param  mixed $id
     * @param  mixed $request
     * @param  mixed $articleRepository
     * @param  mixed $categoryRepository
     * @return Response
     */
    public function articleSearch(Request $request, int $id = -1): Response
    {
        $label = $request->query->get('label');

        $articles = $this->paginator->paginate($this->articleRepository->findByLabelQuery($label), $request->query->getInt('page', 1), 9);
        $categories = $this->categoryRepository->findAll();
        return $this->render('article/index.html.twig', [
            'articles' => $articles,
            'categories' => $categories,
            'id' => $id
        ]);
    }

    #[Route('/sell/{id<\d+>}', name: '_sell')]
    /**
     * 
     * @Security("is_granted('ROLE_RIGHT_SELL_ARTICLE') or is_granted('ROLE_ADMIN')")
     * 
     * sell an article
     *
     * @param  mixed $article
     * @param  mixed $request
     * @return Response
     */
    public function sellArticle(Article $article, Request $request): Response
    {
        // First way to treat the sale : sell each article separately (wihtout session)

        // Get the product quantity
        $quantity = $request->query->get('quantity');
        $stock = $article->getStock();

        //update the stock
        if ($quantity != null) {
            if ($quantity > $stock || $quantity <= 0) {
                $this->flasher->addWarning("Quantité incorrecte!");
            } else {
                $article->setStock($stock - $quantity);
                $sale = new Sale;
                $sale->setQuantitySold($quantity);
                $sale->setArticleSold($article);
                $sale->setDateOfSale(new \DateTime);
                $sale->setPreviousStock($stock);
                $sale->setResponsableOfSale($this->getUser());

                $this->em->persist($sale);
                $this->em->flush();

                $this->flasher->addInfo("Produit vendu!!");
                $this->responsableTracker->saveTracking($this::ARTICLE_SELL_ACTIVITY . $article->getLabel() . "\" | Quantité : " . $quantity, $this->getUser());
            }
        }

        return $this->redirectToRoute('app_article_list');
    }

    #[Route('/history', name: '_history')]
    /**
     * 
     * @Security("is_granted('ROLE_RIGHT_SALES_HISTORY') or is_granted('ROLE_ADMIN')")
     * 
     * history of all sales by responsable
     *
     * @param  mixed $saleRepository
     * @return Response
     */
    public function historyOfsales(SaleRepository $saleRepository): Response
    {

        $sales = $saleRepository->findAll();

        return $this->render('article/history.html.twig', [
            'sales' => $sales
        ]);
    }

    #[Route('/catalogue', name: '_catalogue')]
    /**
     * 
     * @Security("is_granted('ROLE_RIGHT_SALES_MANAGEMENT') or is_granted('ROLE_ADMIN')")
     * 
     * manage the stock of articles
     *
     * @return Response
     */
    public function catalog(): Response
    {
        $articles = $this->articleRepository->findOrderedByCreatedAt();
        return $this->render('article/catalogue.html.twig', [
            'articles' => $articles
        ]);
    }

    #[Route('/add', name: '_add')]
    /**
     * 
     * @Security("is_granted('ROLE_RIGHT_SALES_MANAGEMENT') or is_granted('ROLE_ADMIN')")
     * 
     * add a new Article
     *
     * @param  mixed $request
     * @param  mixed $fileUploader
     * @return Response
     */
    public function addArticle(Request $request, FileUploader $fileUploader): Response
    {
        $article = new Article;
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('imageFileName')->getData();
            if ($image != null) {

                $imageFileName = $fileUploader->upload($image, 'product');
                $article->setImageFileName($imageFileName);
            }
            $article->setCreatedAt(new \DateTime);
            $this->em->persist($article);
            $this->em->flush();

            $this->flasher->addSuccess('Produit ajouté');

            $this->responsableTracker->saveTracking($this::ARTICLE_ADD_ACTIVITY . $article->getLabel() . "\" | Stock : " . $article->getStock(), $this->getUser());

            return $this->redirectToRoute('app_article_catalogue');
        }
        return $this->render('article/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/edit/{id<\d+>}', name: '_edit', methods: ['GET', 'PUT'])]
    /**
     * @Security("is_granted('ROLE_RIGHT_SALES_MANAGEMENT') or is_granted('ROLE_ADMIN')")
     *  
     * edit an article
     *
     * @param  mixed $article
     * @param  mixed $request
     * @param  mixed $fileUploader
     * @return Response
     */
    public function editArticle(Article $article, Request $request, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(ArticleType::class, $article, [
            'method' => 'PUT'
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('imageFileName')->getData();
            if ($image != null) {
                $imageFileName = $fileUploader->upload($image, 'product');
                $article->setImageFileName($imageFileName);
            }

            $this->em->flush();
            $this->flasher->addInfo('Produit édité');
            $this->responsableTracker->saveTracking($this::ARTICLE_EDIT_ACTIVITY . $article->getLabel() . "\"", $this->getUser());

            return $this->redirectToRoute('app_article_catalogue');
        }
        return $this->render('article/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/delete/{id<\d+>}', name: '_delete', methods: ['DELETE'])]
    /**
     * 
     * @Security("is_granted('ROLE_RIGHT_SALES_MANAGEMENT') or is_granted('ROLE_ADMIN')")
     * 
     * delete an article
     *
     * @param  mixed $article
     * @return Response
     */
    public function deleteArticle(Article $article): Response
    {
        $this->responsableTracker->saveTracking($this::ARTICLE_DELETE_ACTIVITY . $article->getLabel() . "\"", $this->getUser());

        $this->em->remove($article);
        $this->em->flush();
        $this->flasher->addInfo('Produit supprimé');

        return $this->redirectToRoute('app_article_catalogue');
    }
}
