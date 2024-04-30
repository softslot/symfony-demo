<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

enum UserRole: string
{
    case User = 'ROLE_USER';
    case Admin = 'ROLE_ADMIN';

    public function isEqual(UserRole $role): bool
    {
        return $this === $role;
    }

    public function isUser(): bool
    {
        return $this === self::User;
    }

    public function isAdmin(): bool
    {
        return $this === self::Admin;
    }
}
