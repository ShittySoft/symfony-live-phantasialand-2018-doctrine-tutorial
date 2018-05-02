<?php

declare(strict_types=1);

namespace Authentication\Value;

final class PasswordHash
{
    /** @var string */
    private $hash;

    private function __construct()
    {
    }

    public static function fromClearText(ClearTextPassword $clearTextPassword) : self
    {
        return self::fromHash(password_hash($clearTextPassword->toString(), \PASSWORD_DEFAULT));
    }

    public static function fromHash(string $hash) : self
    {
        if (0 !== strpos($hash, '$2y$')) {
            throw new \InvalidArgumentException('Invalid hash provided');
        }

        $instance = new self();

        $instance->hash = $hash;

        return $instance;
    }

    public function toString() : string
    {
        return $this->hash;
    }
}









