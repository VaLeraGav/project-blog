<?php

declare(strict_types=1);

namespace App\Tests\Kernel\Repository;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Factory\UserFactory;
use App\Factory\BlogFactory;
use App\Repository\BlogRepository;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class BlogRepositoryTest extends KernelTestCase
{
    use ResetDatabase;
    use Factories;

    public function testSomething(): void
    {
        self::bootKernel();

        $user = UserFactory::createOne();

        BlogFactory::createOne(['user' => $user, 'title' => 'blog title']);
        BlogFactory::createMany(7, ['user' => $user]);

        $blogRepository = static::getContainer()->get(BlogRepository::class);

        $blogs = $blogRepository->getBlogs();

        $this->assertCount(6, $blogs);

        $this->assertSame('blog title', $blogs[0]->getTitle());
    }
}