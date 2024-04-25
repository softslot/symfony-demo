<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\Common\Collections\ArrayCollection;

class User
{
    private Id $id;

    private ?Email $email = null;

    private string $passwordHash;

    private ?string $confirmationToken = null;

    private ?ResetToken $resetToken = null;

    private Status $status;

    /** @var ArrayCollection<Network> */
    private ArrayCollection $networks;

    private \DateTimeImmutable $createdAt;

    private function __construct(Id $id, \DateTimeImmutable $createdAt)
    {
        $this->id = $id;
        $this->createdAt = $createdAt;
        $this->networks = new ArrayCollection();
        $this->status = Status::New;
    }

    public static function signUpByEmail(
        Id $id,
        Email $email,
        string $passwordHash,
        string $confirmationToken,
        \DateTimeImmutable $createdAt,
    ): self {
        $user = new self($id, $createdAt);
        $user->email = $email;
        $user->passwordHash = $passwordHash;
        $user->confirmationToken = $confirmationToken;
        $user->status = Status::Wait;

        return $user;
    }

    public static function signUpByNetwork(
        Id $id,
        string $network,
        string $identity,
        \DateTimeImmutable $createdAt,
    ): self {
        $user = new self($id, $createdAt);
        $user->networks->add(new Network($user, $network, $identity));
        $user->status = Status::Active;

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

    public function id(): Id
    {
        return $this->id;
    }

    public function email(): Email
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
        return $this->status === Status::Wait;
    }

    public function isActive(): bool
    {
        return $this->status === Status::Active;
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

        $this->status = Status::Active;
        $this->confirmationToken = null;
    }

    public function requestPasswordReset(ResetToken $resetToken, \DateTimeImmutable $date): void
    {
        if ($this->email === null) {
            throw new \DomainException('Email is not specified.');
        }

        if ($this->resetToken && !$this->resetToken->isExpiredTo($date)) {
            throw new \DomainException('Resetting is already requested.');
        }

        $this->resetToken = $resetToken;
    }

    public function resetToken(): ?ResetToken
    {
        return $this->resetToken;
    }

    public function passwordReset(\DateTimeImmutable $now, string $passwordHash): void
    {
        if ($this->resetToken === null) {
            throw new \DomainException('Resetting is not requested.');
        }

        if ($this->resetToken->isExpiredTo($now)) {
            throw new \DomainException('Reset token is expired.');
        }

        $this->passwordHash = $passwordHash;
    }
}
