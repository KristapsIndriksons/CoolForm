<?php

namespace CoolForm\DB;

use PDO;

class Connector
{
    const HOST = 'localhost';
    const USER = 'root';
    const PASSWORD = null;
    const DB = 'CoolForm';

    /**
     * @var PDO
     */
    private $pdo;

    /**
     * @return PDO
     */
    public function getPDO(): PDO
    {
        if (!$this->pdo) {
            $this->pdo = new PDO($this->buildDSN(),self::USER,self::PASSWORD);
        }

        return $this->pdo;
    }

    /**
     * @return string
     */
    private function buildDSN(): string
    {
        return sprintf('mysql:host=%s;dbname=%s', self::HOST, self::DB);
    }
}
