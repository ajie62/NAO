<?php
/**
 * Created by PhpStorm.
 * User: jeromebutel
 * Date: 16/04/2018
 * Time: 15:03
 */

namespace App\Controller;

use App\Entity\Observation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
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
     * User profile
     * @Route("/profile", name="user.profile")
     */
    public function profile()
    {
        $user = $this->getUser();
        $listOfObservations = $this->em->getRepository(Observation::class)->findByUser($user);

        return $this->render('user/profile.html.twig', [
            'user' => $this->getUser(),
            'observations' => $listOfObservations,
        ]);
    }
}