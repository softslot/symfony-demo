<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\User\UseCase\Reset;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\ResetToken;
use App\Model\User\Entity\User\User;
use App\Tests\Unit\BaseUnitTestCase;

class RequestTest extends BaseUnitTestCase
{
    public function testSuccess(): void
    {
        $now = new \DateTimeImmutable();
        $token = new ResetToken(self::faker()->text(20), $now->modify('+1 day'));
        $user = $this->buildSignedUpUser();

        $user->requestPasswordReset($token, $now);

        $this->assertNotNull($user->resetToken());
    }

    public function testAlready(): void
    {
        $now = new \DateTimeImmutable();
        $token = new ResetToken(self::faker()->text(20), $now->modify('+1 day'));
        $user = $this->buildSignedUpUser();

        $user->requestPasswordReset($token, $now);

        $this->expectExceptionMessage('Resetting is already requested.');

        $user->requestPasswordReset($token, $now);
    }

    public function testExpired(): void
    {
        $now = new \DateTimeImmutable();

        $user = $this->buildSignedUpUser();

        $token1 = new ResetToken(self::faker()->text(20), $now->modify('+1 day'));
        $user->requestPasswordReset($token1, $now);

        self::assertEquals($token1, $user->resetToken());

        $token2 = new ResetToken(self::faker()->text(20), $now->modify('+3 day'));
        $user->requestPasswordReset($token2, $now->modify('+2 day'));

        self::assertEquals($token2, $user->resetToken());
    }

    public function testWithoutEmail(): void
    {
        $now = new \DateTimeImmutable();
        $token = new ResetToken(self::faker()->text(20), $now->modify('+1 day'));

        $user = $this->buildSignUpByNetwork();

        $this->expectExceptionMessage('Email is not specified.');
        $user->requestPasswordReset($token, $now);
    }

    private function buildSignedUpUser(): User
    {
        return User::signUpByEmail(
            Id::next(),
            new Email(self::faker()->email()),
            self::faker()->text(20),
            self::faker()->text(20),
            new \DateTimeImmutable(),
        );
    }

    private function buildSignUpByNetwork(): User
    {
        return User::signUpByNetwork(
            Id::next(),
            self::faker()->text(20),
            self::faker()->text(20),
            new \DateTimeImmutable(),
        );
    }
}
