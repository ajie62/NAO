<?php
/**
 * Created by PhpStorm.
 * User: Sofyann
 * Date: 30/03/2018
 * Time: 14:23
 */

namespace App\Controller;


use App\Entity\Article;
use App\Entity\Comment;
use function dump;
use function json_encode;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AjaxBlogController extends AbstractController
{
    /**
     * @Route("/blog/comments/add", name="blog.add_Comment")
     * @Method("POST")
     */
    public function addComment(Request $request){
        $comment = new Comment();
        $idArticle = $request->request->get('idArticle');
        $em = $this->getDoctrine()->getManager();
        $comment->setArticle($em->getRepository('App:Article')->find($idArticle));
        $comment->setContent($request->request->get('content'));
        $em->persist($comment);
        $em->flush();
        $response = new Response();
        $data = json_encode(['id' => $comment->getId()]);
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent($data);

        return $response;
    }

    /**
     * @Route("/blog/comments/delete/{id}", name="blog.delete_Comment")
     * @Method("DELETE")
     */
    public function deleteComment(Comment $comment){
        $em = $this->getDoctrine()->getManager();
        $em->remove($comment);
        $em->flush();
        return new Response(null, 204);
    }
}