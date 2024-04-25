<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\Common\Collections\ArrayCollection;

class User
{
    private UserId $id;

    private ?UserEmail $email = null;

    private string $passwordHash;

    private ?string $confirmationToken = null;

    private ?UserResetToken $resetToken = null;

    private UserStatus $status;

    private UserRole $role;

    /** @var ArrayCollection<UserNetwork> */
    private ArrayCollection $networks;

    private \DateTimeImmutable $createdAt;

    private function __construct(UserId $id, \DateTimeImmutable $createdAt)
    {
        $this->id = $id;
        $this->createdAt = $createdAt;
        $this->networks = new ArrayCollection();
        $this->status = UserStatus::New;
        $this->role = UserRole::User;
    }

    public static function signUpByEmail(
        UserId $id,
        UserEmail $email,
        string $passwordHash,
        string $confirmationToken,
        \DateTimeImmutable $createdAt,
    ): self {
        $user = new self($id, $createdAt);
        $user->email = $email;
        $user->passwordHash = $passwordHash;
        $user->confirmationToken = $confirmationToken;
        $user->status = UserStatus::Wait;

        return $user;
    }

    public static function signUpByNetwork(
        UserId $id,
        string $network,
        string $identity,
        \DateTimeImmutable $createdAt,
    ): self {
        $user = new self($id, $createdAt);
        $user->networks->add(new UserNetwork($user, $network, $identity));
        $user->status = UserStatus::Active;

        return $user;
    }

    public function attachNetwork(string $network, string $identity): void
    {
        foreach ($this->networks as $existingNetwork) {
            if ($existingNetwork->isForNetwork($network)) {
                throw new \DomainException('Network is already attached.');
            }
        }

        $this->networks->add($network);
    }

    public function id(): UserId
    {
        return $this->id;
    }

    public function email(): UserEmail
    {
        return $this->email;
    }

    public function passwordHash(): string
    {
        return $this->passwordHash;
    }

    public function confirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    public function isWait(): bool
    {
        return UserStatus::Wait === $this->status;
    }

    public function isActive(): bool
    {
        return UserStatus::Active === $this->status;
    }

    public function networks(): array
    {
        return $this->networks->toArray();
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function confirmSignup(): void
    {
        if ($this->isActive()) {
            throw new \DomainException('User already confirmed.');
        }

        $this->status = UserStatus::Active;
        $this->confirmationToken = null;
    }

    public function requestPasswordReset(UserResetToken $resetToken, \DateTimeImmutable $date): void
    {
        if (!$this->isActive()) {
            throw new \DomainException('User is not active.');
        }

        if (null === $this->email) {
            throw new \DomainException('Email is not specified.');
        }

        if ($this->resetToken && !$this->resetToken->isExpiredTo($date)) {
            throw new \DomainException('Resetting is already requested.');
        }

        $this->resetToken = $resetToken;
    }

    public function resetToken(): ?UserResetToken
    {
        return $this->resetToken;
    }

    public function passwordReset(\DateTimeImmutable $now, string $passwordHash): void
    {
        if (null === $this->resetToken) {
            throw new \DomainException('Resetting is not requested.');
        }

        if ($this->resetToken->isExpiredTo($now)) {
            throw new \DomainException('Reset token is expired.');
        }

        $this->passwordHash = $passwordHash;
    }

    public function changeRole(UserRole $role): void
    {
        $this->role = $role;
    }
}
