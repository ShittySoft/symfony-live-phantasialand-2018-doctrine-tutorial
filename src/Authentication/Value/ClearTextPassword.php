<?php

declare(strict_types=1);

namespace Authentication\Value;

final class ClearTextPassword
{
    private const PASSWORD_MINIMUM_LENGTH = 8;

    /** @var string */
    private $password;

    private function __construct(string $password)
    {
        $this->password = $password;
    }

    public static function fromPassword(string $password) : self
    {
        if (strlen($password) < self::PASSWORD_MINIMUM_LENGTH) {
            throw new \InvalidArgumentException('Password is too short');
        }

        return new self($password);
    }

    public static function forLogin(string $password) : self
    {
        return new self($password);
    }

    public function toString() : string
    {
        return $this->password;
    }
}









