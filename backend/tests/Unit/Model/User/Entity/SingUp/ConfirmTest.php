<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\User\Entity\SingUp;

use App\Tests\Builder\User\UserBuilder;
use App\Tests\Unit\BaseUnitTestCase;

class ConfirmTest extends BaseUnitTestCase
{
    public function testSuccess(): void
    {
        $user = (new UserBuilder())->viaEmail()->build();

        $user->confirmSignup();

        self::assertTrue($user->isActive());
        self::assertFalse($user->isWait());
        self::assertNull($user->confirmationToken());
    }

    public function testAlreadyConfirmed(): void
    {
        $user = (new UserBuilder())->viaEmail()->build();

        $user->confirmSignup();

        $this->expectExceptionMessage('User already confirmed.');

        $user->confirmSignup();
    }
}
