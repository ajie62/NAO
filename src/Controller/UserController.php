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
        return $this->render('user/profile.html.twig', [
            'user' => $this->getUser(),
            'observations' => $listOfObservations,
            'numberArticlesPublished' => $numberArticlesPublished,
            'numberArticlesDrafts' => $numberArticlesDrafts
        ]);
    }

    /**
     * @Route("/profile/articles/draft", name="user.profile_draft_article")
     * @Security("is_granted('ROLE_NATURALISTE')")
     */
    public function userDraftArticles()
    {
        $user = $this->getUser();
        $articles = $this->em->getRepository(Article::class)->findBy(['user' => $user, 'published' => false]);

        return $this->render('user/profileDraft.html.twig', [
           'articles' => $articles
        ]);
    }

    /**
     * @Route("/profile/articles/published", name="user.profile_published_article")
     * @Security("is_granted('ROLE_NATURALISTE')")
     */
    public function userPublishedArticles()
    {
        $user = $this->getUser();
        $articles = $this->em->getRepository(Article::class)->findBy(['user' => $user, 'published' => true]);

        return $this->render('user/profileDraft.html.twig', [
            'articles' => $articles
        ]);
    }
}