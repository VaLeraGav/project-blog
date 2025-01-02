<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Blog;
use App\Filter\BlogFilter;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

class BlogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Blog::class);
    }
    public function getBlogs(): array
    {
        return $this
            ->createQueryBuilder('b')
            ->setMaxResults(6)
            ->getQuery()
            ->getResult();
    }

    public function findByBlogFilter(BlogFilter $blogFilter): QueryBuilder
    {
        $blogs = $this->createQueryBuilder('b')
            ->leftJoin(User::class, 'u', 'WITH', 'u.id = b.user')
            ->where('1 = 1')
        ;

        if ($blogFilter->getUser()) {
            $blogs
                ->andWhere('b.user = :user')
                ->setParameter('user', $blogFilter->getUser())
            ;
        }

        if ($blogFilter->getTitle()) {
            $blogs
                ->andWhere('b.title LIKE :title')
                ->setParameter('title', '%' . $blogFilter->getTitle() . '%')
            ;
        }

        return $blogs;
    }
}
