<?php

declare(strict_types=1);

namespace Authentication\ReadModel;

interface UserExists
{
    public function __invoke(string $username) : bool;
}
