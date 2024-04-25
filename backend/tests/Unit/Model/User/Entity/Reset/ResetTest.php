<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\User\Entity\Reset;

use App\Model\User\Entity\User\UserResetToken;
use App\Tests\Builder\User\UserBuilder;
use App\Tests\Unit\BaseUnitTestCase;

class ResetTest extends BaseUnitTestCase
{
    public function testSuccess(): void
    {
        $user = (new UserBuilder())->viaEmail()->confirmed()->build();
        $now = new \DateTimeImmutable();
        $token = new UserResetToken(self::faker()->uuid(), $now->modify('+1 day'));

        $user->requestPasswordReset($token, $now);

        self::assertNotNull($user->resetToken());

        $user->passwordReset($now, $hash = self::faker()->text(20));

        self::assertNotNull($user->resetToken());
        self::assertEquals($hash, $user->passwordHash());
    }

    public function testExpiredToken(): void
    {
        $user = (new UserBuilder())->viaEmail()->confirmed()->build();
        $now = new \DateTimeImmutable();
        $token = new UserResetToken(self::faker()->uuid(), $now);

        $user->requestPasswordReset($token, $now);

        $this->expectExceptionMessage('Reset token is expired.');

        $user->passwordReset($now->modify('+1 day'), self::faker()->text(20));
    }

    public function testNotRequested(): void
    {
        $user = (new UserBuilder())->viaEmail()->confirmed()->build();
        $now = new \DateTimeImmutable();

        $this->expectExceptionMessage('Resetting is not requested.');

        $user->passwordReset($now, self::faker()->text(20));
    }
}
