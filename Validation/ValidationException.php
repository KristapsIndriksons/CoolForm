<?php

namespace CoolForm\Validation;

/**
 * Class ValidationException
 * @package CoolForm\Validation
 */
class ValidationException extends \Exception
{
    const WARNING = 0;
    const SEVERE = 1;

    /**
     * ValidationException constructor.
     * @param $message
     * @param int $code
     */
    public function __construct($message, $code = self::SEVERE) {
        parent::__construct($message, $code);
    }

    /**
     * @return string
     */
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}