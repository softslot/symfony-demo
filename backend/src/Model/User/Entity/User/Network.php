<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

#[ORM\Entity]
class Network
{
    #[ORM\Id]
    #[ORM\Column(type: 'guid', unique: true)]
    private string $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'networks')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private User $user;

    #[ORM\Column(type: 'string')]
    private string $network;

    #[ORM\Column(type: 'string')]
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
