<?php

namespace CoolForm\Validation;

use CoolForm\Logger\Logger;
use Exception;

/**
 * Class Login
 * @package CoolForm\Validation
 */
class Login
{
    const REGEX_USERNAME = '/[^\s]{5,20}/';
    const REGEX_PASSWORD = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{4,18}$/';
    // Thank you Google
    const REGEX_EMAIL = '/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD';

    /**
     * @var Logger
     */
    protected $logger;

    public function __construct()
    {
        $this->logger = new Logger();
    }

    /**
     * @param array $data
     * @return bool
     */
    public function validateFormData(array $data = []): bool
    {
        try {
            $userOK = $this->validateUsername($data['username'] ?? '');
            $passOK = $this->validatePassword($data['password'] ?? '');
        } catch (Exception $e) {
            $this->logger->warning($e->getMessage());

            return false;
        }

        return $userOK && $passOK;
    }

    /**
     * @param string $username
     * @return bool
     * @throws Exception
     */
    public function validateUsername(string $username = ''): bool
    {
        if (!$username) {
            throw new Exception('No Username specified!');
        };

        return (bool) preg_match(self::REGEX_USERNAME, $username);
    }

    /**
     * @param string $password
     * @return bool
     * @throws Exception
     */
    public function validatePassword(string $password = ''): bool
    {
        if (!$password) {
            throw new Exception('No Password specified!');
        };

        return (bool) preg_match(self::REGEX_PASSWORD, $password);
    }

    /**
     * @param string $email
     * @return bool
     */
    public function validateEmail(string $email = ''): bool
    {
        return (bool) preg_match(self::REGEX_EMAIL, $email);
    }
}
