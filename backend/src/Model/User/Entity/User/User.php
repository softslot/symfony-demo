<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'user_users')]
class User
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'guid', unique: true)]
    private UserId $id;

    #[ORM\Embedded(class: UserName::class)]
    private UserName $name;

    #[ORM\Embedded(class: UserEmail::class, columnPrefix: false)]
    private ?UserEmail $email = null;

    #[ORM\Column(name: 'password_hash', type: 'string', nullable: true)]
    private ?string $passwordHash = null;

    #[ORM\Column(name: 'confirmation_token', type: 'string', nullable: true)]
    private ?string $confirmationToken = null;

    #[ORM\Embedded(class: UserResetToken::class, columnPrefix: 'reset_token_')]
    private ?UserResetToken $resetToken = null;

    #[ORM\Column(type: 'string', length: 16, nullable: false, enumType: UserStatus::class)]
    private UserStatus $status;

    #[ORM\Column(type: 'string', length: 16, nullable: false, enumType: UserRole::class)]
    private UserRole $role;

    /** @var ArrayCollection<int, Network> */
    #[ORM\OneToMany(targetEntity: Network::class, mappedBy: 'user', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $networks;

    #[ORM\Column(name: 'created_at', type: 'date_immutable', nullable: false)]
    private \DateTimeImmutable $createdAt;

    private function __construct(UserId $id, UserName $name, \DateTimeImmutable $createdAt)
    {
        $this->id = $id;
        $this->name = $name;
        $this->createdAt = $createdAt;
        $this->networks = new ArrayCollection();
        $this->status = UserStatus::New;
        $this->role = UserRole::User;
    }

    public static function signUpByEmail(
        UserId $id,
        UserName $name,
        UserEmail $email,
        string $passwordHash,
        string $confirmationToken,
        \DateTimeImmutable $createdAt,
    ): self {
        $user = new self($id, $name, $createdAt);
        $user->email = $email;
        $user->passwordHash = $passwordHash;
        $user->confirmationToken = $confirmationToken;
        $user->status = UserStatus::Wait;

        return $user;
    }

    public static function signUpByNetwork(
        UserId $id,
        UserName $name,
        string $network,
        string $identity,
        \DateTimeImmutable $createdAt,
    ): self {
        $user = new self($id, $name, $createdAt);
        $user->networks->add(new Network($user, $network, $identity));
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

        $this->networks->add(new Network($this, $network, $identity));
    }

    public function id(): UserId
    {
        return $this->id;
    }

    public function name(): UserName
    {
        return $this->name;
    }

    public function email(): UserEmail
    {
        return $this->email;
    }

    public function passwordHash(): ?string
    {
        return $this->passwordHash;
    }

    public function confirmationToken(): ?string
    {
        return $this->confirmationToken;
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
        if ($this->status->isActive()) {
            throw new \DomainException('User already confirmed.');
        }

        $this->status = UserStatus::Active;
        $this->confirmationToken = null;
    }

    public function requestPasswordReset(UserResetToken $resetToken, \DateTimeImmutable $date): void
    {
        if (!$this->status->isActive()) {
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

    public function status(): UserStatus
    {
        return $this->status;
    }

    public function role(): UserRole
    {
        return $this->role;
    }

    public function changeRole(UserRole $role): void
    {
        if ($this->role->isEqual($role)) {
            throw new \DomainException('Role is already same.');
        }

        $this->role = $role;
    }

    #[ORM\PostLoad]
    private function checkEmbeds(): void
    {
        if ($this->resetToken->isEmpty()) {
            $this->resetToken = null;
        }
    }
}
