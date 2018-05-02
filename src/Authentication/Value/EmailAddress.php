<?php

declare(strict_types=1);

namespace Authentication\Value;

final class EmailAddress
{
    /** @var string */
    private $emailAddress;

    private function __construct()
    {
    }

    public static function for(string $emailAddress) : self
    {
        if (! filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException(\sprintf(
                'Email address "%s" is in an invalid format',
                $emailAddress
            ));
        }

        $instance = new self();

        $instance->emailAddress = $emailAddress;

        return $instance;
    }

    public function toString() : string
    {
        return $this->emailAddress;
    }
}









