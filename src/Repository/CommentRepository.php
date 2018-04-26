<?php
/**
 * Created by PhpStorm.
 * User: jeromebutel
 * Date: 09/04/2018
 * Time: 14:49
 */

namespace App\Repository;

use App\Entity\Article;
use Doctrine\ORM\EntityRepository;

class CommentRepository extends EntityRepository
{
    public function numberComments(Article $article){
        return $this->createQueryBuilder('comment')
            ->select('COUNT(comment)')
            ->andWhere('comment.article = :article')
            ->setParameter('article', $article)
            ->getQuery()
            ->getSingleScalarResult();
    }
}