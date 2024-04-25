<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use Faker;
use PHPUnit\Framework\TestCase;

class BaseUnitTestCase extends TestCase
{
    protected function faker(): Faker\Generator
    {
        return Faker\Factory::create();
    }
}
