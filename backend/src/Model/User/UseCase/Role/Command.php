<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Role;

use App\Model\User\Entity\User\UserRole;
use Symfony\Component\Validator\Constraints as Assert;

readonly class Command
{
    public function __construct(
        #[Assert\Uuid]
        public string $userId,
        public UserRole $userRole
    ) {
    }
}
