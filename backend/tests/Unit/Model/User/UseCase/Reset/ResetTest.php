<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\User\UseCase\Reset;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\ResetToken;
use App\Model\User\Entity\User\User;
use App\Tests\Unit\BaseUnitTestCase;

class ResetTest extends BaseUnitTestCase
{
    public function testSuccess(): void
    {
        $user = $this->buildSignedUpByEmailUser();
        $now = new \DateTimeImmutable();
        $token = new ResetToken(self::faker()->uuid(), $now->modify('+1 day'));

        $user->requestPasswordReset($token, $now);

        self::assertNotNull($user->resetToken());

        $user->passwordReset($now, $hash = self::faker()->text(20));

        self::assertNotNull($user->resetToken());
        self::assertEquals($hash, $user->passwordHash());
    }

    public function testExpiredToken(): void
    {
        $user = $this->buildSignedUpByEmailUser();

        $now = new \DateTimeImmutable();
        $token = new ResetToken(self::faker()->uuid(), $now);

        $user->requestPasswordReset($token, $now);

        $this->expectExceptionMessage('Reset token is expired.');
        $user->passwordReset($now->modify('+1 day'), self::faker()->text(20));
    }

    public function testNotRequested(): void
    {
        $user = $this->buildSignedUpByEmailUser();

        $now = new \DateTimeImmutable();

        $this->expectExceptionMessage('Resetting is not requested.');
        $user->passwordReset($now, self::faker()->text(20));
    }

    private function buildSignedUpByEmailUser(): User
    {
        return User::signUpByEmail(
            Id::next(),
            new Email(self::faker()->email()),
            self::faker()->text(20),
            self::faker()->text(20),
            new \DateTimeImmutable(),
        );
    }
}
