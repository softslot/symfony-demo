<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

enum UserStatus: string
{
    case New = 'new';
    case Wait = 'wait';
    case Active = 'active';

    public function isNew(): bool
    {
        return $this === self::New;
    }

    public function isWait(): bool
    {
        return $this === self::Wait;
    }

    public function isActive(): bool
    {
        return $this === self::Active;
    }
}
