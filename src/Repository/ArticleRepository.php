<?php
/**
 * Created by PhpStorm.
 * User: Sofyann
 * Date: 29/03/2018
 * Time: 13:10
 */

namespace App\Repository;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;

class ArticleRepository extends EntityRepository
{
    public function findAllPublishedArticlesOrderByMoreRecentDate(){
        return $this->createQueryBuilder('article')
            ->andWhere('article.published = true')
            ->orderBy('article.createdAt', 'ASC')
            ->getQuery()
            ->execute();
    }

    public function totalArticles(){
        return $this->createQueryBuilder('article')
                ->select('COUNT(article)')
                ->getQuery()
                ->getSingleScalarResult();
    }

    public function totalDraftsOrPublishedArticles(User $user, bool $bool){
        return $this->createQueryBuilder('article')
            ->select('COUNT(article)')
            ->andWhere('article.published = :published')
            ->setParameter('published', $bool)
            ->andWhere('article.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findPreviousArticle(Article $article){
        return $this->createQueryBuilder('article')
            ->select('article')
            ->andWhere('article.id < :articleId')
            ->setParameter('articleId', $article->getId())
            ->andWhere('article.published = true')
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getQuery()
            ->execute();
    }

    public function findNextArticle(Article $article){
        return $this->createQueryBuilder('article')
            ->select('article')
            ->andWhere('article.id > :articleId')
            ->setParameter('articleId', $article->getId())
            ->andWhere('article.published = true')
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getQuery()
            ->execute();
    }
}