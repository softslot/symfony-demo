<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Network\Auth;

use App\Model\Flusher;
use App\Model\User\Entity\User\UserId;
use App\Model\User\Entity\User\UserNetwork;
use App\Model\User\Entity\User\User;
use App\Model\User\Entity\User\UserRepository;
use App\Model\User\Exception\UserAlreadyExistsException;

readonly class Handler
{
    public function __construct(
        private UserRepository $users,
        private Flusher $flusher,
    ) {
    }

    /**
     * @throws UserAlreadyExistsException
     */
    public function handle(Command $command): void
    {
        if ($this->users->hasByNetworkIdentity($command->network, $command->identity)) {
            throw new UserAlreadyExistsException("User with network '{$command->network}' already exists.");
        }

        $user = User::signUpByNetwork(
            UserId::next(),
            $command->network,
            $command->identity,
            new \DateTimeImmutable()
        );

        $this->users->add($user);

        $this->flusher->flush();
    }
}
