<?php

namespace CoolForm\Logger;

use Monolog\Handler\StreamHandler;
use Monolog\Logger as Monolog;

class Logger extends Monolog
{
    public function __construct()
    {
        parent::__construct('main');

        try {
            $this->pushHandler(new StreamHandler(__DIR__ . '/logs/main.log', Logger::DEBUG));
        } catch (\Exception $e) {
            return $this;
        }

        return $this;
    }
}
