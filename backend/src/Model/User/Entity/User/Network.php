<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Ramsey\Uuid\Uuid;

class Network
{
    private string $id;

    private User $user;

    private string $network;

    private string $identity;

    public function __construct(User $user, string $network, string $identity)
    {
        $this->id = Uuid::uuid7()->toString();
        $this->user = $user;
        $this->network = $network;
        $this->identity = $identity;
    }

    public function isForNetwork(string $network): bool
    {
        return mb_strtolower($this->network) === mb_strtolower($network);
    }

    public function network(): string
    {
        return $this->network;
    }

    public function identity(): string
    {
        return $this->identity;
    }
}
