<?php
/**
 * Created by PhpStorm.
 * User: jeromebutel
 * Date: 19/04/2018
 * Time: 13:33
 */

namespace App\Service;

use App\Entity\Contact;
use App\Entity\User;

class EmailManager
{
    const EMAIL = 'contact@nosamislesoiseaux.ovh';

    private $mailer;
    private $twig;

    /**
     * EmailManager constructor.
     * @param \Swift_Mailer $mailer
     * @param \Twig_Environment $twig
     */
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * Contact message sent to the association team
     * @param Contact $contact
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sendContactMail(Contact $contact)
    {
        $body = $this->twig->render('emails/contact.html.twig', ['contact' => $contact]);
        $title = 'Nouveau message de '.htmlspecialchars($contact->getMail());

        $message = (new \Swift_Message($title))
            ->setFrom(self::EMAIL)
            ->setTo(self::EMAIL)
            ->setReplyTo($contact->getMail())
            ->setBody($body, 'text/html');

        $this->mailer->send($message);
    }

    /**
     * Welcome message sent after registration
     * @param User $user
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sendWelcomeMail(User $user)
    {
        $body = $this->twig->render('emails/welcome.html.twig', ['user' => $user]);
        $title = "Bienvenue parmi nous !";

        $message = (new \Swift_Message($title))
            ->setFrom(self::EMAIL)
            ->setTo($user->getMail())
            ->setBody($body, 'text/html');

        $this->mailer->send($message);
    }
}