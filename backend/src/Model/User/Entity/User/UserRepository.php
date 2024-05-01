<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;

class UserRepository
{
    private EntityRepository $repo;

    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
        $this->repo = $this->em->getRepository(User::class);
    }

    public function add(User $user): void
    {
        $this->em->persist($user);
    }

    public function getById(UserId $id): User
    {
        if (!$user = $this->repo->find($id->value())) {
            throw new EntityNotFoundException("User not found by id: {$id->value()}.");
        }

        return $user;
    }

    public function getByEmail(UserEmail $email): User
    {
        if (!$user = $this->repo->findOneBy(['email.value' => $email->value()])) {
            throw new EntityNotFoundException("User not found by email: {$email->value()}.");
        }

        return $user;
    }

    public function findByConfirmationToken(string $token): ?User
    {
        return $this->repo->findOneBy(['confirmationToken' => $token]);
    }

    public function findByResetToken(string $token): ?User
    {
        return $this->repo->findOneBy(['resetToken.token' => $token]);
    }

    public function hasByEmail(UserEmail $email): bool
    {
        return $this->repo->createQueryBuilder('u')
                ->select('COUNT(u.id)')
                ->andWhere('u.email.value = :email')
                ->setParameter('email', $email->value())
                ->getQuery()
                ->getSingleScalarResult() > 0;
    }

    public function hasByNetworkIdentity(string $network, string $identity): bool
    {
        return $this->repo->createQueryBuilder('u')
                ->select('COUNT(u.id)')
                ->join('u.networks', 'n')
                ->andWhere('n.network = :network')
                ->andWhere('n.identity = :identity')
                ->setParameter('network', $network)
                ->setParameter('identity', $identity)
                ->getQuery()
                ->getSingleScalarResult() > 0;
    }
}
