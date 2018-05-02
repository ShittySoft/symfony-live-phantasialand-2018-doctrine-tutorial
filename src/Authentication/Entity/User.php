<?php

namespace Authentication\Entity;

use Authentication\Notification\NotifyUserOfRegistration;
use Authentication\ReadModel\UserExists;

class User
{
    private function __construct()
    {
    }

    public function id() : string
    {
        return $this->emailAddress;
    }

    public static function register(
        string $emailAddress,
        string $clearTextPassword,
        UserExists $userExists,
        NotifyUserOfRegistration $notify
    ) : self {
        if (! filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException(\sprintf(
                'Email address "%s" is in an invalid format',
                $emailAddress
            ));
        }

        if ($userExists->__invoke($emailAddress)) {
            throw new \InvalidArgumentException(\sprintf(
                'User with email address "%s" already exists',
                $emailAddress
            ));
        }

        $user = new self();

        $user->emailAddress = $emailAddress;
        $user->passwordHash = password_hash($clearTextPassword, \PASSWORD_DEFAULT);

        if (! \is_string($user->passwordHash)) {
            throw new \RuntimeException('Couldn\'t hash the damn password, and PHP is a shitty language');
        }

        $notify->__invoke($user);

        return $user;
    }
}
