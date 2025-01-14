<?php

declare(strict_types=1);

namespace Tests\Kernel\Service;

use App\Factory\UserFactory;
use App\Repository\BlogRepository;
use App\Repository\UserRepository;
use App\Service\HttpClient;
use App\Service\NewsGrabber;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class NewsGrabberTest extends KernelTestCase
{
    use ResetDatabase;
    use Factories;

    /**
     * Проблема теста в том что он ходит в стороний сервис
     */
    /*
    public function testNotRight(): void
    {
        self::bootKernel();

        $user = UserFactory::createOne();

        $userRepository = $this->createMock(UserRepository::class);
        $userRepository->method('find')->willReturn($user->object()); // нужно именно обьект
        static::getContainer()->set(UserRepository::class, $userRepository);

        $newGrabber = static::getContainer()->get(NewsGrabber::class);
        assert($newGrabber instanceof NewsGrabber);

        $logger = $this->createMock(LoggerInterface::class);

        $newGrabber->setLogger($logger)->importNews(); // начали видеть методы

        $blogRepository = static::getContainer()->get(BlogRepository::class);
        assert($blogRepository instanceof BlogRepository);

        $blogs = $blogRepository->getBlogs();

        self::assertCount(6, $blogs);
    }
    */

    public function testRight(): void
    {
        self::bootKernel();

        $user = UserFactory::createOne();

        $userRepository = $this->createMock(UserRepository::class);
        $userRepository->method('find')->willReturn($user->object());
        static::getContainer()->set(UserRepository::class, $userRepository);

        $httpClient = $this->createMock(HttpClient::class);
        $httpClient
            ->method('get')
            ->willReturnCallback(function ($url) {
                if ('https://www.engadget.com/news/' == $url) {
                    return file_get_contents('tests/DataProvider/index.html');
                } else {
                    static $index = 0;
                    return file_get_contents('tests/DataProvider/news' . ++$index . '.html');
                }
            })
        ;

        static::getContainer()->set(HttpClient::class, $httpClient);

        $newsGrabber = static::getContainer()->get(NewsGrabber::class);
        assert($newsGrabber instanceof NewsGrabber);

        $logger = $this->createMock(LoggerInterface::class);

        $newsGrabber->setLogger($logger)->importNews();

        $blogRepository = static::getContainer()->get(BlogRepository::class);
        assert($blogRepository instanceof BlogRepository);

        $blogs = $blogRepository->findAll();
        self::assertCount(24, $blogs);

        $items = [];
        foreach ($blogs as $blog) {
            $items[] = ['title' => $blog->getTitle(), 'text' => $blog->getText()];
        }

        self::assertSame(
            json_decode(file_get_contents('tests/DataProvider/expected.json'), true),
            $items
        );
    }
}
