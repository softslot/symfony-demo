<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Reset\Request;

readonly class Command
{
    public function __construct(
        public string $email,
    ) {
    }
}