<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\SignUp\Confirm;

readonly class Command
{
    public function __construct(
        public string $token,
    ) {
    }
}
