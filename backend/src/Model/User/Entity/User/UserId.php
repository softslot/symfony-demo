<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

#[ORM\Embeddable]
readonly class UserId
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'guid', unique: true)]
    private string $value;

    public function __construct(string $value)
    {
        Assert::notEmpty($value);
        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public static function next(): self
    {
        return new self(Uuid::uuid7()->toString());
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
