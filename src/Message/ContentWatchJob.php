<?php

declare(strict_types=1);

namespace App\Message;

class ContentWatchJob
{
    public function __construct(
        private readonly int $content,
    ) {
    }

    public function getContent(): int
    {
        return $this->content;
    }
}
