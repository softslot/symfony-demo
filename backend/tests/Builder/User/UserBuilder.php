<?php

declare(strict_types=1);

namespace App\Tests\Builder\User;

use App\Model\User\Entity\User\User;
use App\Model\User\Entity\User\UserEmail;
use App\Model\User\Entity\User\UserId;
use Faker;

class UserBuilder
{
    private UserId $id;
    private \DateTimeImmutable $createdAt;

    private ?UserEmail $email = null;
    private string $passwordHash;
    private ?string $confirmationToken = null;
    private bool $confirmed = false;

    private ?string $network = null;
    private ?string $identity = null;

    private Faker\Generator $faker;

    public function __construct()
    {
        $this->id = UserId::next();
        $this->createdAt = new \DateTimeImmutable();
        $this->faker = Faker\Factory::create();
    }

    public function viaEmail(
        ?UserEmail $email = null,
        ?string $passwordHash = null,
        ?string $confirmationToken = null
    ): self {
        $clone = clone $this;
        $clone->email = $email ?? new UserEmail($this->faker->email());
        $clone->passwordHash = $passwordHash ?? $this->faker->text(10);
        $clone->confirmationToken = $confirmationToken ?? $this->faker->text(10);

        return $clone;
    }

    public function vieNetwork(?string $network = null, ?string $identity = null): self
    {
        $clone = clone $this;
        $clone->network = $network ?? $this->faker->text(10);
        $clone->identity = $identity ?? $this->faker->text(10);

        return $clone;
    }

    public function confirmed(): self
    {
        $clone = clone $this;
        $clone->confirmed = true;

        return $clone;
    }

    public function build(): User
    {
        if ($this->email) {
            $user = User::signUpByEmail(
                $this->id,
                $this->email,
                $this->passwordHash,
                $this->confirmationToken,
                $this->createdAt
            );

            if ($this->confirmed) {
                $user->confirmSignup();
            }

            return $user;
        }

        return User::signUpByNetwork(
            $this->id,
            $this->network,
            $this->identity,
            $this->createdAt
        );
    }
}
