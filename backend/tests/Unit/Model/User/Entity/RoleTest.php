<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\User\Entity;

use App\Model\User\Entity\User\UserRole;
use App\Tests\Builder\User\UserBuilder;
use App\Tests\Unit\BaseUnitTestCase;

class RoleTest extends BaseUnitTestCase
{
    public function testSuccess(): void
    {
        $user = (new UserBuilder())->viaEmail()->build();

        $user->changeRole(UserRole::Admin);

        self::assertFalse($user->role()->isUser());
        self::assertTrue($user->role()->isAdmin());
    }

    public function testAlready(): void
    {
        $user = (new UserBuilder())->viaEmail()->build();

        $this->expectExceptionMessage('Role is already same.');

        $user->changeRole(UserRole::User);
    }
}
