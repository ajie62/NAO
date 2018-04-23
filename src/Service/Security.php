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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
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
     * Security constructor.
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder,
        UrlGeneratorInterface $urlGenerator)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param FormInterface $form
     * @return RedirectResponse
     */
    public function handleSubmittedRegistrationForm(FormInterface $form): RedirectResponse
    {
        /** @var User $user */
        $user = $form->getData();
        $password = $this->passwordEncoder->encodePassword($user, $user->getPassword());
        $user->setPassword($password);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return new RedirectResponse( $this->urlGenerator->generate('user.profile') );
    }

}