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
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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

        return $this->render('user/profileDraft.html.twig', ['articles' => $articles]);
    }
}