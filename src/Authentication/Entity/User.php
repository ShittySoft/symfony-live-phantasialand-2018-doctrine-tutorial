<?php

namespace Authentication\Entity;

use Authentication\Notification\NotifyUserOfRegistration;
use Authentication\ReadModel\UserExists;
use Authentication\Value\EmailAddress;

class User
{
    /** @var EmailAddress */
    private $emailAddress;

    private function __construct()
    {
    }

    public function id() : EmailAddress
    {
        return $this->emailAddress;
    }

    public static function register(
        EmailAddress $emailAddress,
        string $clearTextPassword,
        UserExists $userExists,
        NotifyUserOfRegistration $notify
    ) : self {
        if ($userExists->__invoke($emailAddress)) {
            throw new \InvalidArgumentException(\sprintf(
                'User with email address "%s" already exists',
                $emailAddress->toString()
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
