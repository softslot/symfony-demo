<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Embeddable]
readonly class UserName
{
    #[ORM\Column(type: 'string')]
    private string $first;

    #[ORM\Column(type: 'string')]
    private string $last;

    public function __construct(string $first, string $last)
    {
        Assert::notEmpty($first);
        Assert::notEmpty($last);

        $this->first = $first;
        $this->last = $last;
    }

    public function first(): string
    {
        return $this->first;
    }

    public function last(): string
    {
        return $this->last;
    }

    public function full(): string
    {
        return implode(' ', [$this->first, $this->last]);
    }
}
