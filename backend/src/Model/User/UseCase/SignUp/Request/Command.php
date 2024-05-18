<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\SignUp\Request;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Email]
        public ?string $email = null,
        #[Assert\NotBlank]
        #[Assert\Length(min: 6)]
        public ?string $password = null,
        public ?string $firstName = null,
        public ?string $lastName = null,
    ) {
    }
}
