<?php

declare(strict_types=1);

namespace Authentication\Value;

final class ClearTextPassword
{
    private const PASSWORD_MINIMUM_LENGTH = 8;

    /** @var string */
    private $password;

    private function __construct()
    {
    }

    public static function fromPassword(string $password) : self
    {
        if (strlen($password) < self::PASSWORD_MINIMUM_LENGTH) {
            throw new \InvalidArgumentException('Password is too short');
        }

        $instance = new self();

        $instance->password = $password;

        return $instance;
    }

    public function toString() : string
    {
        return $this->password;
    }
}









