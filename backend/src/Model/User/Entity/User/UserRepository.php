<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

interface UserRepository
{
    public function add(User $user): void;

    public function getById(UserId $id): User;

    public function getByEmail(UserEmail $email): User;

    public function findByConfirmToken(string $token): ?User;

    public function findByResetToken(string $token): ?User;

    public function hasByEmail(UserEmail $email): bool;

    public function hasByNetworkIdentity(string $network, string $identity): bool;
}
