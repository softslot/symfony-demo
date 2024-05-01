<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\SignUp\Request;

class Command
{
    public function __construct(
        public ?string $email = null,
        public ?string $password = null,
        public ?string $firstName = null,
        public ?string $lastName = null,
    ) {
    }
}
