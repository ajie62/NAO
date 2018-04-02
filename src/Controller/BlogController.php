<?php
/**
 * Created by PhpStorm.
 * User: Sofyann
 * Date: 27/03/2018
 * Time: 17:09
 */

namespace App\Controller;


use App\Entity\Article;
use function dump;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BlogController
 * @package App\Controller
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/blog/list", name="blog.list_article")
     */
    public function listArticle()
    {
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository('App:Article')->findAll();

        return $this->render('blog/listArticle.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/blog/management/add", name="blog.add_article")
     */
    public function addArticle(Request $request)
    {
        $article = new Article();
        $form = $this->createForm('App\Form\ArticleFormType', $article);
        if ($request->isMethod('POST'))
        {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($article);
                $em->flush();
            }
        }
        return $this->render('blog/addArticle.html.twig', [
           'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/blog/show/{id}", name="blog.show_article")
     */
    public function showArticle(Article $article)
    {
        $em = $this->getDoctrine()->getManager();
        $comments = $em->getRepository('App:Comment')->findBy([
            'article' => $article
        ]);
        dump($comments);
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
        $form = $this->get('form.factory')->create();
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->remove($article);
                $em->flush();
                return $this->redirectToRoute('blog.list_article');
            }
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
        $form = $this->createForm('App\Form\ArticleFormType', $article);
        if ($request->isMethod('POST'))
        {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($article);
                $em->flush();
            }
        }
        return $this->render('blog/editArticle.html.twig', [
            'form' => $form->createView(),
            'article' => $article
        ]);
    }
}