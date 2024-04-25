<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Reset\Request;

use App\Model\Flusher;
use App\Model\User\Entity\User\UserEmail;
use App\Model\User\Entity\User\UserRepository;
use App\Model\User\Service\ResetTokenGenerator;
use App\Model\User\Service\ResetTokenSender;

readonly class Handler
{
    public function __construct(
        private UserRepository $users,
        private ResetTokenGenerator $resetTokenGenerator,
        private ResetTokenSender $resetTokenSender,
        private Flusher $flusher,
    ) {
    }

    public function handle(Command $command): void
    {
        $user = $this->users->getByEmail(new UserEmail($command->email));

        $user->requestPasswordReset(
            $this->resetTokenGenerator->generate(),
            new \DateTimeImmutable(),
        );

        $this->flusher->flush();

        $this->resetTokenSender->send($user->email(), $user->resetToken()->token());
    }
}
