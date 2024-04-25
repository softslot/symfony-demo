<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Network\Auth;

readonly class Command
{
    public function __construct(
        public string $network,
        public string $identity,
    ) {
    }
}
