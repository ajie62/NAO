<?php
/**
 * Created by PhpStorm.
 * User: Sofyann
 * Date: 30/03/2018
 * Time: 14:23
 */

namespace App\Controller;

use App\Entity\Comment;
use function count;
use function json_encode;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AjaxBlogController extends AbstractController
{
    /**
     * @Route("/blog/comments/add", name="blog.add_Comment")
     * @Method("POST")
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function addComment(Request $request, ValidatorInterface $validator){
        $em = $this->getDoctrine()->getManager();
        $comment = new Comment();
        $idArticle = $request->request->get('idArticle');
        $comment->setArticle($em->getRepository('App:Article')->find($idArticle));
        $comment->setContent($request->request->get('content'));
        $comment->setUser($this->getUser());
        $errors = $validator->validate($comment);
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        if (count($errors) > 0 || $this->is_html($request->request->get('content'))){
            $response->setStatusCode('403');
        } else {
            $em->persist($comment);
            $em->flush();
            $data = json_encode(['id' => $comment->getId()]);
            $response->setContent($data);
        }
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

    function is_html($string)
    {
        return preg_match("/<[^<]+>/",$string,$m) != 0;
    }
}