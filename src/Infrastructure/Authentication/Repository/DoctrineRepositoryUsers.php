<?php

declare(strict_types=1);

namespace Infrastructure\Authentication\Repository;

use Authentication\Entity\User;
use Authentication\Repository\Users;
use Authentication\Value\EmailAddress;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Webmozart\Assert\Assert;

final class DoctrineRepositoryUsers implements Users
{
    /**
     * @var ObjectRepository
     */
    private $users;

    /**
     * @var ObjectManager
     */
    private $persistence;

    public function __construct(
        ObjectRepository $users,
        ObjectManager $persistence
    ) {
        Assert::same($users->getClassName(), User::class);

        $this->users = $users;
        $this->persistence = $persistence;
    }

    public function get(EmailAddress $emailAddress) : User
    {
        return $this->users->find($emailAddress->toString());
    }

    public function store(User $user) : void
    {
        $this->persistence->persist($user);
        $this->persistence->flush();
    }
}