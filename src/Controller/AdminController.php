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
use Doctrine\ORM\EntityManagerInterface;
use function dump;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use function strtolower;
use function substr;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("/users/{id}", name="admin.users_edit")
     */
    public function editUser(User $user, Request $request){
        $form = $this->createForm(EditProfilType::class, $user);
//        $oldPass = 'Particulier1!';
//        dump($this->passwordEncoder->isPasswordValid($user, $oldPass));die;
        if ($request->isMethod('POST')){
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()){
                $user = $form->getData();
                $this->em->persist($user);
                $this->em->flush();
            }
        }
        return $this->render('admin/editUser.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/users/generatePassword/{id}", name="admin.users_generatePassword")
     */
    public function generatePasswordUser(User $user){
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
//        $mailer = $this->get('App\Service\EmailManager');
//        $mailer->sendNewPassword($user, $newPass);
//        dump($newPass);die;
        return $this->redirectToRoute('admin.users_edit', ['id' => $user->getId()]);
    }

    /**
     * @Route("/users/passusertonaturaliste/{id}", name="admin.users_passNaturalist")
     */
    public function passUserToNaturalisteRole(User $user){
        if ($user->getRoles() == [$user::NATURALISTE]){
            $user->setRoles([$user::PARTICULIER]);
        } elseif ($user->getRoles() == [$user::PARTICULIER]){
            $user->setRoles([$user::NATURALISTE]);
        }
        $this->em->persist($user);
        $this->em->flush();
        return $this->redirectToRoute('admin.users_edit', ['id' => $user->getId()]);
    }

    /**
     * @Route("/users/block/{id}", name="admin.users_block")
     */
    public function blockUser(User $user){
        if ($user->isActive()){
            $user->setIsActive(false);
        } else {
            $user->setIsActive(true);
        }
        $this->em->persist($user);
        $this->em->flush();
        return $this->redirectToRoute('admin.users_edit', ['id' => $user->getId()]);
    }

    /**
     * @Route("/users/delete/{id}", name="admin.users_delete")
     */
    public function deleteUser(User $user){
        $this->em->remove($user);
        $this->em->flush();
        return $this->redirectToRoute('admin.index');
    }
}