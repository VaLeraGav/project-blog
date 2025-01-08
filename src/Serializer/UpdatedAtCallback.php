<?php

declare(strict_types=1);

namespace App\Serializer;

use DateTimeInterface;

class UpdatedAtCallback
{
    public function __invoke(string|DateTimeInterface|null $innerObject): DateTimeInterface|string|null
    {
        if (null === $innerObject) {
            return null;
        }

        if (!($innerObject instanceof DateTimeInterface)) {
            return $innerObject;
        }

        return $innerObject->format('H:i:s Y-m-d');
    }
}
