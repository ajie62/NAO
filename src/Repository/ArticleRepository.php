<?php
/**
 * Created by PhpStorm.
 * User: Sofyann
 * Date: 29/03/2018
 * Time: 13:10
 */

namespace App\Repository;

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
}