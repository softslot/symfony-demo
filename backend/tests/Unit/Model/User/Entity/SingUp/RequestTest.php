<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\User\Entity\SingUp;

use App\Model\User\Entity\User\User;
use App\Model\User\Entity\User\UserEmail;
use App\Model\User\Entity\User\UserId;
use App\Model\User\Entity\User\UserName;
use App\Tests\Unit\BaseUnitTestCase;

class RequestTest extends BaseUnitTestCase
{
    public function testSuccess(): void
    {
        $user = User::signUpByEmail(
            $id = UserId::next(),
            $name = new UserName(self::faker()->firstName(), self::faker()->lastName()),
            $email = new UserEmail(self::faker()->email()),
            $passwordHash = self::faker()->text(20),
            $confirmToken = self::faker()->text(20),
            $createdAt = new \DateTimeImmutable(),
        );

        self::assertTrue($user->status()->isWait());
        self::assertFalse($user->status()->isActive());

        self::assertEquals($id, $user->id());
        self::assertEquals($name->full(), $user->name()->full());
        self::assertEquals($email, $user->email());
        self::assertEquals($passwordHash, $user->passwordHash());
        self::assertEquals($confirmToken, $user->confirmationToken());
        self::assertEquals($createdAt, $user->createdAt());

        self::assertTrue($user->role()->isUser());
    }
}
