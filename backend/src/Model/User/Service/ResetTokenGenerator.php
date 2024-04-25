<?php

declare(strict_types=1);

namespace App\Model\User\Service;

use App\Model\User\Entity\User\UserResetToken;
use Ramsey\Uuid\Uuid;

readonly class ResetTokenGenerator
{
    public function __construct(
        private \DateInterval $interval
    ) {
    }

    public function generate(): UserResetToken
    {
        return new UserResetToken(
            Uuid::uuid4()->toString(),
            (new \DateTimeImmutable())->add($this->interval)
        );
    }
}
