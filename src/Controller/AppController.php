<?php
/**
 * Created by PhpStorm.
 * User: jeromebutel
 * Date: 10/04/2018
 * Time: 00:25
 */

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\User;
use App\Form\ContactType;
use App\Form\LoginType;
use App\Form\RegistrationType;
use App\Service\EmailManager;
use App\Service\SecurityInterface;
use Doctrine\ORM\EntityManagerInterface;
use ReCaptcha\ReCaptcha;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AppController extends AbstractController
{
    /** @var EntityManagerInterface $em */
    private $em;
    /** @var EmailManager $emailManager */
    private $emailManager;

    public function __construct(EntityManagerInterface $entityManager, EmailManager $emailManager)
    {
        $this->em = $entityManager;
        $this->emailManager = $emailManager;
    }

    /**
     * App homepage
     * @Route("/", name="app.homepage")
     *
     * @param SecurityInterface $securityHandler
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function homepage(SecurityInterface $securityHandler, Request $request)
    {
        if ($this->getUser())
            return $this->redirectToRoute('user.profile');

        $form = $this->createForm('App\Form\RegistrationType');
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
            return $securityHandler->handleSubmittedRegistrationForm($form);

        return $this->render('app/index.html.twig', [
            'registration_form' => $form->createView()
        ]);
    }

    /**
     * Contact page
     * @Route("/contact", name="app.contact")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function contact(Request $request)
    {
        $contact = new Contact();
        $recaptcha = new ReCaptcha('6LekM1QUAAAAALGZ9zQG8Wx4akJvDHGE8zXUEk4q');
        $response = $recaptcha->verify($request->request->get('g-recaptcha-response'), $request->getClientIp());

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);
        $message = null;

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$response->isSuccess()) {
                $message = "Le reCAPTCHA n'a pas été correctement entré. Veuillez réessayer.";
            } else {
                $this->emailManager->sendContactMail($contact);
                $this->em->persist($contact);
                $this->em->flush();

                $this->addFlash('mail_sent', 'Votre message a bien été envoyé !');

                return $this->redirectToRoute('app.contact');
            }
        }

        return $this->render('app/contact.html.twig', [
            'form' => $form->createView(),
            'captchaError' => $message,
        ]);
    }

    /**
     * @Route("/terms-of-use", name="app.terms_of_use")
     */
    public function termsOfUse()
    {
        return $this->render('app/termsOfUse.html.twig');
    }

    /**
     * @Route("/about", name="app.about")
     */
    public function about()
    {
        return $this->render('app/about.html.twig');
    }
}