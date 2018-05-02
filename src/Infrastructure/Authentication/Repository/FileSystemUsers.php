<?php

declare(strict_types=1);

namespace Infrastructure\Authentication\Repository;

use Authentication\Entity\User;
use Authentication\Repository\Users;
use Authentication\Value\EmailAddress;

final class FileSystemUsers implements Users
{
    public function get(EmailAddress $emailAddress) : User
    {
        return unserialize(file_get_contents(
            __DIR__ . '/../../../../data/' . $emailAddress->toString() . '.user.txt'
        ));
    }

    public function store(User $user) : void
    {
        file_put_contents(
            __DIR__ . '/../../../../data/' . $user->id()->toString() . '.user.txt',
            serialize($user)
        );
    }
}