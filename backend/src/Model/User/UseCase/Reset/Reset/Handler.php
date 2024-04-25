<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Reset\Reset;

use App\Model\Flusher;
use App\Model\User\Entity\User\UserRepository;
use App\Model\User\Service\PasswordHasher;

readonly class Handler
{
    public function __construct(
        private UserRepository $users,
        private PasswordHasher $passwordHasher,
        private Flusher $flusher,
    ) {
    }

    public function handle(Command $command): void
    {
        $user = $this->users->findByResetToken($command->token);
        if (null === $user) {
            throw new \DomainException('Incorrect or confirmed token.');
        }

        $user->passwordReset(
            new \DateTimeImmutable(),
            $this->passwordHasher->hash($command->password),
        );

        $this->flusher->flush();
    }
}
