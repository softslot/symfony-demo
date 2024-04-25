<?php

declare(strict_types=1);

namespace App\Model\User\Service;

use App\Model\User\Entity\User\UserEmail;

interface ResetTokenSender
{
    public function send(UserEmail $email, string $token): void;
}
