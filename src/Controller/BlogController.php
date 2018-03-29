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

    }

    /**
     * @Route("/blog/management/add", name="blog.add_article")
     */
    public function addArticle(Request $request)
    {
        $form = $this->createForm('App\Form\ArticleFormType');
        if ($request->isMethod('POST'))
        {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $article = new Article();
                $em->persist($article);
                $em->flush();
            }
        }
        return $this->render('blog/addArticle.html.twig', [
           'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/blog/show", name="blog.show_article")
     */
    public function showArticle()
    {

    }

    /**
     * @Route("/blog/management/delete/{id}", name="blog.delete_article")
     */
    public function deleteArticle()
    {

    }

    /**
     * @Route("/blog/management/edit/{id}", name="blog.edit_article")
     */
    public function editArticle()
    {

    }
}