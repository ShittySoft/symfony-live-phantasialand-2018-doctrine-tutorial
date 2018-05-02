<?php

declare(strict_types=1);

use Authentication\Entity\User;
use Authentication\Notification\NotifyUserOfRegistration;
use Authentication\ReadModel\UserExists;
use Authentication\Repository\Users;
use Authentication\Value\ClearTextPassword;
use Authentication\Value\EmailAddress;

require_once __DIR__ . '/../vendor/autoload.php';

$users = new class implements Users {
    public function get(EmailAddress $emailAddress) : User
    {
        return unserialize(file_get_contents(
            __DIR__ . '/../data/' . $emailAddress->toString() . '.user.txt'
        ));
    }

    public function store(User $user) : void
    {
        file_put_contents(
            __DIR__ . '/../data/' . $user->id()->toString() . '.user.txt',
            serialize($user)
        );
    }
};

$users->store(User::register(
    EmailAddress::for($_POST['emailAddress']),
    ClearTextPassword::fromPassword($_POST['password']),
    new class ($users) implements UserExists {
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
    },
    new class implements NotifyUserOfRegistration {
        public function __invoke(User $user) : void
        {
            error_log($user->id()->toString() . ' registered!');
        }
    }
));

