<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\SingUp\Confirm;

use App\Model\Flusher;
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
        $user = $this->users->findByConfirmToken($command->token);
        if ($user === null) {
            throw new \DomainException('Incorrect confirm token.');
        }

        $user->confirmSignup();

        $this->flusher->flush();
    }
}
