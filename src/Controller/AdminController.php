<?php
/**
 * Created by PhpStorm.
 * User: Sofyann
 * Date: 18/04/2018
 * Time: 15:06
 */

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class AdminController
 * @Route("/admin")
 * @Security("is_granted('ROLE_ADMIN')")
 * @package App\Controller
 */
class AdminController extends Controller
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @Route("/", name="admin.index")
     */
    public function stats(){
        $statsObs = $this->em->getRepository('App:Observation')->findNumberOfObservationsAndStats();
        $statsUsers = $this->em->getRepository('App:User')->findStatsUser();
        $statsArticles = $this->em->getRepository('App:Article')->totalArticles();

        return $this->render('admin/stats.html.twig', [
            'statsObs' => $statsObs,
            'statsUsers' => $statsUsers,
            'statsArticles' => $statsArticles
        ]);
    }

    /**
     * @Route("/users", name="admin.users")
     */
    public function users(){
        $users = $this->em->getRepository('App:User')->findBy([], ['subscribedAt' => 'DESC']);
        return $this->render('admin/users.html.twig', ['users' => $users]);
    }

    /**
     * @Route("/observations", name="admin.observations")
     */
    public function observations(){
        $observations = $this->em->getRepository('App:Observation')->findBy([], ['observedAt' => 'DESC']);
        return $this->render('admin/observations.html.twig', ['observations' => $observations]);
    }

}