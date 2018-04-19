<?php
/**
 * Created by PhpStorm.
 * User: Sofyann
 * Date: 18/04/2018
 * Time: 15:06
 */

namespace App\Controller;


use function dump;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    /**
     * @Route("/administration/stats", name="admin.stats")
     */
    public function stats(){
        $em = $this->getDoctrine()->getManager();
        $statsObs = $em->getRepository('App:Observation')->findNumberOfObservationsAndStats();
        $statsUsers = $em->getRepository('App:User')->findStatsUser();
        $statsArticles = $em->getRepository('App:Article')->totalArticles();

        return $this->render('admin/stats.html.twig', [
           'statsObs' => $statsObs,
           'statsUsers' => $statsUsers,
           'statsArticles' => $statsArticles
        ]);
    }

    /**
     * @Route("/administration/users", name="admin.users")
     */
    public function users(){
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('App:User')->findBy([], ['subscribedAt' => 'DESC']);
//        dump($em);die;
        return $this->render('admin/users.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/administration/observations", name="admin.observations")
     */
    public function observations(){
        $em = $this->getDoctrine()->getManager();
        $observations = $em->getRepository('App:Observation')->findBy([], ['observedAt' => 'DESC']);
        return $this->render('admin/observations.html.twig', [
            'observations' => $observations
        ]);
    }

}