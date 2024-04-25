<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\SingUp\Request;

use App\Model\Flusher;
use App\Model\User\Entity\User\User;
use App\Model\User\Entity\User\UserEmail;
use App\Model\User\Entity\User\UserId;
use App\Model\User\Entity\User\UserRepository;
use App\Model\User\Exception\UserAlreadyExistsException;
use App\Model\User\Service\ConfirmTokenGenerator;
use App\Model\User\Service\ConfirmTokenSender;
use App\Model\User\Service\PasswordHasher;

readonly class Handler
{
    public function __construct(
        private UserRepository $users,
        private PasswordHasher $passwordHasher,
        private ConfirmTokenGenerator $tokenGenerator,
        private ConfirmTokenSender $tokenSender,
        private Flusher $flusher,
    ) {
    }

    /**
     * @throws UserAlreadyExistsException
     */
    public function handle(Command $command): void
    {
        $email = new UserEmail($command->email);

        if ($this->users->hasByEmail($email)) {
            throw new UserAlreadyExistsException("User with email: {$email->value()}, already exists.");
        }

        $user = User::signUpByEmail(
            UserId::next(),
            $email,
            $this->passwordHasher->hash($command->password),
            $token = $this->tokenGenerator->generate(),
            new \DateTimeImmutable(),
        );

        $this->users->add($user);

        $this->tokenSender->send($email, $token);

        $this->flusher->flush();
    }
}
