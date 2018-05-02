<?php

declare(strict_types=1);

namespace Infrastructure\Authentication\Notification;

use Authentication\Entity\User;
use Authentication\Notification\NotifyUserOfRegistration;

class NotifyUserOfRegistrationViaStderr implements NotifyUserOfRegistration
{
    public function __invoke(User $user) : void
    {
        \error_log($user->id()->toString() . ' registered!');
    }
}