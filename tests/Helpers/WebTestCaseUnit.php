<?php

declare(strict_types=1);

namespace Tests\Helpers;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class WebTestCaseUnit extends WebTestCase
{
    use ResetDatabase;
    use Factories;
}
