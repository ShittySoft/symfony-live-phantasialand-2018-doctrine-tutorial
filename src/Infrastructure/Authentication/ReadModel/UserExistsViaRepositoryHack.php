<?php

declare(strict_types=1);

namespace Infrastructure\Authentication\ReadModel;

use Authentication\ReadModel\UserExists;
use Authentication\Repository\Users;
use Authentication\Value\EmailAddress;

final class UserExistsViaRepositoryHack implements UserExists
{
    /**
     * @var Users
     */
    private $users;

    public function __construct(Users $users)
    {
        $this->users = $users;
    }

    public function __invoke(EmailAddress $username) : bool
    {
        try {
            return (bool) $this->users->get($username);
        } catch (\Throwable $e) {
            return false;
        }
    }
}