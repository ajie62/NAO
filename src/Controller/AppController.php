<?php
/**
 * Created by PhpStorm.
 * User: jeromebutel
 * Date: 10/04/2018
 * Time: 00:25
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AppController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * App homepage
     * @Route("/", name="app.homepage")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function homepage()
    {
        $form = $this->createForm('App\Form\RegistrationType');
        return $this->render('app/index.html.twig', [
            'registration_form' => $form->createView()
        ]);
    }

    /**
     * Logout
     * @Route("/logout", name="app.logout")
     */
    public function logout()
    {
    }
}