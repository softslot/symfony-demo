<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Webmozart\Assert\Assert;

class Email
{
    private string $value;

    public function __construct(string $value)
    {
        Assert::notEmpty($value);
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email');
        }

        $this->value = mb_strtolower($value);
    }

    public function value(): string
    {
        return $this->value;
    }
}
