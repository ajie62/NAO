<?php
/**
 * Created by PhpStorm.
 * User: jeromebutel
 * Date: 19/04/2018
 * Time: 22:59
 */

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class Security implements SecurityInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;
    /**
     * @var TokenStorageInterface
     */
    private $storage;
    /**
     * @var EmailManager
     */
    private $emailManager;

    /**
     * Security constructor.
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param UrlGeneratorInterface $urlGenerator
     * @param TokenStorageInterface $storage
     * @param EmailManager $emailManager
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder,
        UrlGeneratorInterface $urlGenerator,
        TokenStorageInterface $storage,
        EmailManager $emailManager)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->urlGenerator = $urlGenerator;
        $this->storage = $storage;
        $this->emailManager = $emailManager;
    }

    /**
     * @param FormInterface $form
     * @param FlashBagInterface $flashBag
     * @return RedirectResponse
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function handleSubmittedRegistrationForm(FormInterface $form, FlashBagInterface $flashBag): RedirectResponse
    {
        /** @var User $user */
        $user = $form->getData();
        $password = $this->passwordEncoder->encodePassword($user, $user->getPassword());
        $user->setPassword($password);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->emailManager->sendWelcomeMail($user);

        $providerKey = 'db';
        $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());
        $this->storage->setToken($token);

        $flashBag->add(
            'registration_success',
            'Votre inscription a été réalisée avec succès. Bienvenue parmi nous !'
        );

        return new RedirectResponse( $this->urlGenerator->generate('user.profile') );
    }
}
