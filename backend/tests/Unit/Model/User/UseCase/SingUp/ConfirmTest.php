<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\User\UseCase\SingUp;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\User;
use App\Tests\Unit\BaseUnitTestCase;

class ConfirmTest extends BaseUnitTestCase
{
    public function testSuccess(): void
    {
        $user = $this->buildSignedUpUser();

        $user->confirmSignup();

        self::assertTrue($user->isActive());
        self::assertFalse($user->isWait());

        self::assertNull($user->confirmationToken());
    }

    public function testAlreadyConfirmed(): void
    {
        $user = $this->buildSignedUpUser();

        $user->confirmSignup();
        $this->expectExceptionMessage('User already confirmed.');
        $user->confirmSignup();
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
}
