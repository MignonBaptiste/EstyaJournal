<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\User;
use App\Repository\CommentRepository;
use Knp\Component\Pager\PaginatorInterface; 
use App\Form\CommentType;
use App\Repository\CategoryRepository;
use App\Repository\NewsRepository;
use App\Service\NavCategory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    private $navCategory;
    

    public function __construct(NavCategory $navCategory)
    {
        $this->navCategory = $navCategory;
    }

    #[Route('/', name: 'app_accueil')]
    public function index(NewsRepository $newsRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $newsRepository->paginationQuery(),
            $request->query->get('page', 1),
            8
        );

        return $this->render('accueil/index.html.twig',[
            'categoryList' => $this->navCategory->category(),
            'pagination' => $pagination
        ]);
    }

    #[Route('/news/{id<[0-9]+>}', name: 'app_new_show')]
    public function newsShow($id, NewsRepository $newsRepository,Request $request, EntityManagerInterface $entityManager, CommentRepository $commentRepository):Response
    {
        $newsId = $newsRepository->find($id);

        $comment = new Comment();
        $form = $this->createForm(CommentType::class,$comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user = $this->getUser();
            $comment->setAuthor($user);
            $comment->setNews($newsId);
            $comment->setCreatedAt(new \dateTime);
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('success','Votre commentaire a été envoyé');
        }

        
        return $this->render('accueil/news_single.html.twig',[
            'single_news'=>$newsRepository->find($newsId),
            'categoryList' => $this->navCategory->category(),
            'form'=>$form->createView(),
            'comments'=>$commentRepository->findBy(['news'=>$newsId])
        ]);
    }

    #[Route('/news/{id<[0-9]+>}/category', name: 'app_new_by_category_show')]
    public function NewsBysCategory($id,NewsRepository $newsRepository, CategoryRepository $categoryRepository):Response
    {
        $idCategory = $categoryRepository->find($id);
        $categoryName= $categoryRepository->findOneBy(['id'=>$id],[]);
        
        $newsByCategory = $newsRepository->findBy(['category'=>$idCategory],[]);

        return $this->render('accueil/newsByCategory.html.twig',[
            'news'=>$newsByCategory,
            'categoryList' => $this->navCategory->category(),
            'category'=>$categoryName
        ]);
    }
}
