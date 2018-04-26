<?php
/**
 * Created by PhpStorm.
 * User: jérômebutel
 * Date: 16/04/2018
 * Time: 15:03
 */

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Observation;
use App\Form\EditProfilType;
use App\Form\NewPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use function dump;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
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
     * User profile
     * @Route("/profile", name="user.profile")
     * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
     */
    public function profile()
    {
        $user = $this->getUser();
        $listOfObservations = $this->em->getRepository(Observation::class)->findByUser($user);
        $numberArticlesPublished = $this->em->getRepository(Article::class)->totalDraftsOrPublishedArticles($user, true);
        $numberArticlesDrafts = $this->em->getRepository(Article::class)->totalDraftsOrPublishedArticles($user, false);
        $numberOfAwaitingObservations = $this->em->getRepository(Observation::class)->findNumberOfAwaitingObservations($user);

        return $this->render('user/profile.html.twig', [
            'user' => $this->getUser(),
            'observations' => $listOfObservations,
            'numberArticlesPublished' => $numberArticlesPublished,
            'numberArticlesDrafts' => $numberArticlesDrafts,
            'numberOfAwaitingObservations' => $numberOfAwaitingObservations
        ]);
    }

    /**
     * @Route("/profile/draft-articles", name="user.profile_draft_article")
     * @Security("is_granted('ROLE_NATURALISTE')")
     */
    public function userDraftArticles()
    {
        $user = $this->getUser();
        $articles = $this->em->getRepository(Article::class)->findBy(['user' => $user, 'published' => false]);
        $hasDraftArticles = count($articles) > 0;

        if (!$hasDraftArticles)
            return $this->redirectToRoute('user.profile');

        return $this->render('user/profileDraft.html.twig', ['articles' => $articles]);
    }

    /**
     * @Route("/profile/published-articles", name="user.profile_published_article")
     * @Security("is_granted('ROLE_NATURALISTE')")
     */
    public function userPublishedArticles()
    {
        $user = $this->getUser();
        $articles = $this->em->getRepository(Article::class)->findBy(['user' => $user, 'published' => true]);
        $hasPublishedArticles = count($articles) > 0;

        if (!$hasPublishedArticles)
            return $this->redirectToRoute('user.profile');

        return $this->render('user/profilePublished.html.twig', ['articles' => $articles]);
    }

    /**
     * @Route("/profile/edit", name="user.edit_profile")
     */
    public function editProfil(Request $request){
        $user = $this->getUser();
        $form = $this->createForm(EditProfilType::class, $user);
        $newPasswordForm = $this->createForm(NewPasswordType::class);
        $errors = '';
        if ($request->isMethod('POST')){
            $form->handleRequest($request);
            $newPasswordForm->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()){
                $this->em->persist($user);
                $this->em->flush();
            }
            if ($newPasswordForm->isSubmitted() && $newPasswordForm->isValid()){
                $data = $newPasswordForm->getData();
                $oldPassword = $data['oldPassword'];
                $newPass1 = $data['newPasswordFirst'];
                $newPass2 = $data['newPasswordSecond'];
                if ($this->passwordEncoder->isPasswordValid($user, $oldPassword)){
                    if ($newPass1 == $newPass2){
                        $user->setPassword($this->passwordEncoder->encodePassword($user, $newPass1));
                        $this->em->persist($user);
                        $this->em->flush();
                        $errors = '';
                    } else {
                        $errors = 'Vous devez indiquer un nouveau mot de passe identique sur les deux champs.';
                    }
                } else {
                    $errors = 'L\'ancien mot de passe que vous avez indiqué est incorrecte';
                }
            }
        }
        return $this->render('user/profileEdit.html.twig', [
           'user' => $user,
           'form' => $form->createView(),
           'newPasswordForm' => $newPasswordForm->createView(),
           'errors' => $errors
        ]);

    }
}