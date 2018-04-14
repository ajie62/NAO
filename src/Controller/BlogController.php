<?php
/**
 * Created by PhpStorm.
 * User: Sofyann
 * Date: 27/03/2018
 * Time: 17:09
 */

namespace App\Controller;


use App\Entity\Article;
use App\Entity\Observation;
use Doctrine\ORM\EntityManagerInterface;
use function dump;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BlogController
 * @package App\Controller
 */
class BlogController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @Route("/blog/list", name="blog.list_article")
     */
    public function listArticle()
    {
        $articles = $this->em->getRepository('App:Article')->findAllPublishedArticlesOrderByMoreRecentDate();
        return $this->render('blog/listArticle.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/blog/management/add", name="blog.add_article")
     */
    public function addArticle(Request $request)
    {
        return $this->setArticle($request, new Article());
    }

    /**
     * @Route("/blog/show/{id}", name="blog.show_article")
     */
    public function showArticle(Article $article)
    {
        $comments = $this->em->getRepository('App:Comment')->findBy([
            'article' => $article
        ]);
        $form = $this->createForm('App\Form\CommentArticleType');
        return $this->render('blog/showArticle.html.twig', [
            'article' => $article,
            'formComment' => $form->createView(),
            'comments' => $comments
        ]);
    }

    /**
     * @Route("/blog/management/delete/{id}", name="blog.delete_article")
     */
    public function deleteArticle(Article $article, Request $request)
    {
        $form = $this->createFormBuilder()->setMethod('DELETE')->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() && $request->isMethod('DELETE')){
            $this->em->remove($article);
            $this->em->flush();
            return $this->redirectToRoute('blog.list_article');
        }
        return $this->render('blog/deleteArticle.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/blog/management/edit/{id}", name="blog.edit_article")
     */
    public function editArticle(Article $article, Request $request)
    {
        return $this->setArticle($request, $article);
    }

    private function setArticle(Request $request, Article $article){
        $form = $this->createForm('App\Form\ArticleFormType', $article);
        if ($request->isMethod('POST'))
        {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()){
                $this->em->persist($article);
                $this->em->flush();
                return $this->redirectToRoute('blog.list_article');
            }
        }
        return $this->render('blog/setArticle.twig', [
            'form' => $form->createView()
        ]);
    }


}