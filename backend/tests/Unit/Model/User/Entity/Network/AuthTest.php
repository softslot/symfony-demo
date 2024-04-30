<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\User\Entity\Network;

use App\Model\User\Entity\User\Network;
use App\Model\User\Entity\User\User;
use App\Model\User\Entity\User\UserId;
use App\Tests\Unit\BaseUnitTestCase;

class AuthTest extends BaseUnitTestCase
{
    public function testSuccess(): void
    {
        $user = User::signUpByNetwork(
            UserId::next(),
            $network = self::faker()->text(20),
            $identity = self::faker()->text(20),
            new \DateTimeImmutable(),
        );

        self::assertTrue($user->status()->isActive());

        self::assertCount(1, $networks = $user->networks());
        self::assertInstanceOf(Network::class, $first = reset($networks));
        self::assertEquals($network, $first->network());
        self::assertEquals($identity, $first->identity());
    }

    //    public function testAlreadyExists(): void
    //    {
    //        $user = User::signUpByNetwork(
    //            Id::next(),
    //            $network = self::faker()->text(20),
    //            $identity = self::faker()->text(20),
    //            new \DateTimeImmutable(),
    //        );
    //
    //        $this->assertTrue(true);
    //
    //        $this->expectExceptionMessage('Network already exists');
    //
    //        $user = User::signUpByNetwork(
    //            Id::next(),
    //            $network = self::faker()->text(20),
    //            $identity = self::faker()->text(20),
    //            new \DateTimeImmutable(),
    //        );
    //    }
}
