<?php

declare(strict_types=1);

namespace App\ReadModel\User;

use Doctrine\DBAL\Connection;

readonly class UserFetcher
{
    public function __construct(
        private Connection $connection,
    ) {
    }

    public function existsByResetToken(string $token): bool
    {
        return $this->connection->createQueryBuilder()
            ->select('COUNT (*)')
            ->from('user_users')
            ->where('reset_token_reset = :token')
            ->setParameter('token', $token)
            ->executeQuery()->fetchFirstColumn() > 0;
    }
}
