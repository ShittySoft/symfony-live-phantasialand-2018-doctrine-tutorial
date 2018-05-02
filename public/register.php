<?php

declare(strict_types=1);

use Authentication\Entity\User;
use Authentication\Notification\NotifyUserOfRegistration;
use Authentication\ReadModel\UserExists;
use Authentication\Repository\Users;

require_once __DIR__ . '/../vendor/autoload.php';

$users = new class implements Users {
    public function get(string $emailAddress) : User
    {
        return unserialize(file_get_contents(
            __DIR__ . '/../data/' . $emailAddress . '.user.txt'
        ));
    }

    public function store(User $user) : void
    {
        file_put_contents(
            __DIR__ . '/../data/' . $user->id() . '.user.txt',
            serialize($user)
        );
    }
};

$users->store(User::register(
    $_POST['emailAddress'],
    $_POST['password'],
    new class ($users) implements UserExists {
        /**
         * @var Users
         */
        private $users;

        public function __construct(Users $users)
        {
            $this->users = $users;
        }

        public function __invoke(string $username) : bool
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
            error_log($user->id() . ' registered!');
        }
    }
));

