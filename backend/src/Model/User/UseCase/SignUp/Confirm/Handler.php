<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\SignUp\Confirm;

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
        $user = $this->users->findByConfirmationToken($command->token);
        if (null === $user) {
            throw new \DomainException('Incorrect confirm token.');
        }

        $user->confirmSignup();

        $this->flusher->flush();
    }
}
