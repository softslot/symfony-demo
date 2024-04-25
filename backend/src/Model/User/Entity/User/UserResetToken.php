<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Webmozart\Assert\Assert;

class UserResetToken
{
    private string $token;

    private \DateTimeImmutable $expiresAt;

    public function __construct(string $token, \DateTimeImmutable $expiresAt)
    {
        Assert::notEmpty($token);
        $this->token = $token;
        $this->expiresAt = $expiresAt;
    }

    public function token(): string
    {
        return $this->token;
    }

    public function expiresAt(): \DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function isExpiredTo(\DateTimeImmutable $date): bool
    {
        return $this->expiresAt->diff($date)->invert === 0;
    }
}
