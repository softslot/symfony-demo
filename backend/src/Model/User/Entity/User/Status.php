<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

enum Status: string
{
    case New = 'new';
    case Wait = 'wait';
    case Active = 'active';
}
