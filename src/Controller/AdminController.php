<?php
/**
 * Created by PhpStorm.
 * User: Sofyann
 * Date: 18/04/2018
 * Time: 15:06
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\EditProfilType;
use App\Service\EmailManager;
use Doctrine\ORM\EntityManagerInterface;
use function dump;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use function strtolower;
use function substr;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->em = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
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
        $users = $this->em->getRepository('App:User')->findUsersOrderedDesc();
        return $this->render('admin/users.html.twig', ['users' => $users]);
    }

    /**
     * @Route("/observations", name="admin.observations")
     */
    public function observations(){
        $observations = $this->em->getRepository('App:Observation')->findBy([], ['observedAt' => 'DESC']);
        return $this->render('admin/observations.html.twig', ['observations' => $observations]);
    }

    /**
     * @Route("/users/{id}", name="admin.users_edit")
     * @param User $user
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editUser(User $user, Request $request){
        $form = $this->createForm(EditProfilType::class, $user);
        if ($request->isMethod('POST')){
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()){
                $user = $form->getData();
                $this->em->persist($user);
                $this->em->flush();

                $this->addFlash('user_edited', 'L\'utilisateur a bien été édité.');
            }
        }
        return $this->render('admin/editUser.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/users/generatePassword/{id}", name="admin.users_generatePassword")
     * @param User $user
     * @param EmailManager $emailManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function generatePasswordUser(User $user, EmailManager $emailManager)
    {
        $date = new \DateTime('now');
        $newPass =
            $user->getId().
            'N'
            .substr($user->getFirstname(), 0,1)
            .strtolower(substr($user->getLastname(),0,1))
            .$date->format('mday')
            .$date->format('mon')
            .$date->format('y')
            .$date->format('h')
            .$date->format('m')
            .'!';
        $user->setPassword($this->passwordEncoder->encodePassword($user, $newPass));
        $this->em->persist($user);
        $this->em->flush();

        $emailManager->sendNewPassword($user, $newPass);

        $this->addFlash(
            'user_edited',
            'Un nouveau mot de passe a été généré pour l\'utilisateur.'
        );

        return $this->redirectToRoute('admin.users_edit', ['id' => $user->getId()]);
    }

    /**
     * @Route("/users/passusertonaturaliste/{id}", name="admin.users_passNaturalist")
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function passUserToNaturalisteRole(User $user){
        if ($user->getRoles() == [$user::NATURALISTE]){
            $user->setRoles([$user::PARTICULIER]);
        } elseif ($user->getRoles() == [$user::PARTICULIER]){
            $user->setRoles([$user::NATURALISTE]);
        }
        $this->em->persist($user);
        $this->em->flush();

        $this->addFlash('user_edited', 'Le rôle de l\'utilisateur a bien été modifié.');

        return $this->redirectToRoute('admin.users_edit', ['id' => $user->getId()]);
    }

    /**
     * @Route("/users/block/{id}", name="admin.users_block")
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function blockUser(User $user){
        if ($user->isActive()){
            $user->setIsActive(false);
        } else {
            $user->setIsActive(true);
        }
        $this->em->persist($user);
        $this->em->flush();

        $status = $user->isActive() ? 'débloqué' : 'bloqué';
        $this->addFlash('user_edited', 'L\'utilisateur a bien été '.$status);

        return $this->redirectToRoute('admin.users_edit', ['id' => $user->getId()]);
    }

    /**
     * @Route("/users/delete/{id}", name="admin.users_delete")
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteUser(User $user){
        $this->em->remove($user);
        $this->em->flush();

        $this->addFlash('user_edited', 'L\'utilisateur a bien été supprimé.');

        return $this->redirectToRoute('admin.index');
    }
}