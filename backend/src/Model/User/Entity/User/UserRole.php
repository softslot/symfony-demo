<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

enum UserRole: string
{
    case User = 'ROLE_USER';
    case Admin = 'ROLE_ADMIN';

    public function isUser(): bool
    {
        return self::User === $this;
    }

    public function isAdmin(): bool
    {
        return self::Admin === $this;
    }
}
