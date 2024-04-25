<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\User\Entity\Reset;

use App\Model\User\Entity\User\UserResetToken;
use App\Tests\Builder\User\UserBuilder;
use App\Tests\Unit\BaseUnitTestCase;

class RequestTest extends BaseUnitTestCase
{
    public function testSuccess(): void
    {
        $user = (new UserBuilder())->viaEmail()->confirmed()->build();
        $now = new \DateTimeImmutable();
        $token = new UserResetToken(self::faker()->text(20), $now->modify('+1 day'));

        $user->requestPasswordReset($token, $now);

        $this->assertNotNull($user->resetToken());

        $user->passwordReset($now, $hash = self::faker()->text(10));

        $this->assertNotNull($user->resetToken());
        $this->assertEquals($hash, $user->passwordHash());
    }

    public function testAlready(): void
    {
        $user = (new UserBuilder())->viaEmail()->confirmed()->build();
        $now = new \DateTimeImmutable();
        $token = new UserResetToken(self::faker()->text(20), $now->modify('+1 day'));

        $user->requestPasswordReset($token, $now);

        $this->expectExceptionMessage('Resetting is already requested.');

        $user->requestPasswordReset($token, $now);
    }

    public function testExpired(): void
    {
        $user = (new UserBuilder())->viaEmail()->confirmed()->build();
        $now = new \DateTimeImmutable();
        $token1 = new UserResetToken(self::faker()->text(20), $now->modify('+1 day'));

        $user->requestPasswordReset($token1, $now);

        self::assertEquals($token1, $user->resetToken());

        $token2 = new UserResetToken(self::faker()->text(20), $now->modify('+3 day'));
        $user->requestPasswordReset($token2, $now->modify('+2 day'));

        self::assertEquals($token2, $user->resetToken());
    }

    public function testWithoutEmail(): void
    {
        $user = (new UserBuilder())->vieNetwork()->build();
        $now = new \DateTimeImmutable();
        $token = new UserResetToken(self::faker()->text(20), $now->modify('+1 day'));

        $this->expectExceptionMessage('Email is not specified.');

        $user->requestPasswordReset($token, $now);
    }

    public function testNotConfirmed(): void
    {
        $user = (new UserBuilder())->viaEmail()->build();
        $now = new \DateTimeImmutable();
        $token = new UserResetToken(self::faker()->text(20), $now->modify('+1 day'));

        $this->expectExceptionMessage('User is not active.');

        $user->requestPasswordReset($token, $now);
    }
}
