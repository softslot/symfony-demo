<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Role;

use App\Model\Flusher;
use App\Model\User\Entity\User\UserId;
use App\Model\User\Entity\User\UserRepository;

readonly class Handler
{
    public function __construct(
        private UserRepository $users,
        private Flusher $flusher,
    ) {
    }

    public function handle(Command $command): void
    {
        $user = $this->users->getById(new UserId($command->userId));

        $user->changeRole($command->userRole);

        $this->flusher->flush();
    }
}
