<?php

declare(strict_types=1);

namespace Authentication\Notification;

use Authentication\Entity\User;

interface NotifyUserOfRegistration
{
    public function __invoke(User $user) : void;
}
