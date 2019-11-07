<?php

namespace CoolForm\Encoder;

/**
 * Class Password
 * @package CoolForm\Encoder
 */
class Password
{
    /**
     * @param string $password
     * @return string
     */
    public static function encode(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public static function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
