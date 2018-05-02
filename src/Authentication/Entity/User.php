<?php

namespace Authentication\Entity;

use Authentication\Notification\NotifyUserOfRegistration;
use Authentication\ReadModel\UserExists;
use Authentication\Value\ClearTextPassword;
use Authentication\Value\EmailAddress;
use Authentication\Value\PasswordHash;

class User
{
    /** @var EmailAddress */
    private $emailAddress;

    /** @var PasswordHash */
    private $passwordHash;

    private function __construct()
    {
    }

    public function id() : EmailAddress
    {
        return $this->emailAddress;
    }

    public static function register(
        EmailAddress $emailAddress,
        ClearTextPassword $clearTextPassword,
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
        $user->passwordHash = PasswordHash::fromClearText($clearTextPassword);

        $notify->__invoke($user);

        return $user;
    }

    public function logIn(ClearTextPassword $password) : bool
    {
        return $this->passwordHash->matches($password);
    }
}
