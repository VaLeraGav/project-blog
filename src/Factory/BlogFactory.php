<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Blog;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Blog>
 */
final class BlogFactory extends PersistentProxyObjectFactory
{
    public function __construct()
    {
    }

    public static function class(): string
    {
        return Blog::class;
    }

    protected function defaults(): array|callable
    {
        return [
            'blockedAt' => self::faker()->dateTime(),
            'createdAt' => self::faker()->dateTime(),
            'description' => self::faker()->text(),
            'status' => self::faker()->text(),
            'text' => self::faker()->text(),
            'title' => self::faker()->text(255),
            'updatedAt' => self::faker()->dateTime(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Blog $blog): void {})
        ;
    }
}
