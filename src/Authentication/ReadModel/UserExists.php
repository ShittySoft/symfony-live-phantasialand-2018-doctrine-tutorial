<?php

declare(strict_types=1);

namespace Authentication\ReadModel;

use Authentication\Value\EmailAddress;

interface UserExists
{
    public function __invoke(EmailAddress $username) : bool;
}
