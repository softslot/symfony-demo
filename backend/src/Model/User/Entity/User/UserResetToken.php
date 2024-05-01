<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Embeddable]
readonly class UserResetToken
{
    #[ORM\Column(type: 'string', unique: true, nullable: true)]
    private string $token;

    #[ORM\Column(type: 'date_immutable', nullable: true)]
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

    /** @internal for postLoad callback */
    public function isEmpty(): bool
    {
        return empty($this->token);
    }
}
