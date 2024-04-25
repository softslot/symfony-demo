<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Reset\Reset;

readonly class Command
{
    public function __construct(
        public string $token,
        public string $password,
    ) {
    }
}
