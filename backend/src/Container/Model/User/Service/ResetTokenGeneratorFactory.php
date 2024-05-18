<?php

declare(strict_types=1);

namespace App\Container\Model\User\Service;

use App\Model\User\Service\ResetTokenGenerator;

class ResetTokenGeneratorFactory
{
    public static function create(string $interval): ResetTokenGenerator
    {
        return new ResetTokenGenerator(new \DateInterval($interval));
    }
}
