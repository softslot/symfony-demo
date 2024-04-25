<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\SingUp\Confirm;

readonly class Command
{
    public function __construct(
        public string $token,
    ) {
    }
}
