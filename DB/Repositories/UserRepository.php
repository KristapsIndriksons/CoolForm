<?php

namespace CoolForm\DB\Repositories;

use CoolForm\DB\Connector;

/**
 * Class UserRepository
 * Fetch user data from DB
 *
 * @package CoolForm
 */
class UserRepository
{
    const USER_TABLE = 'users';

    /**
     * @var Connector
     */
    private $db;

    /**
     * UserRepository constructor.
     */
    public function __construct()
    {
        $this->db = new Connector();
    }

    /**
     * @param string $username
     * @param string $passHash
     */
    public function getUser(string $username, string $passHash)
    {
        $x = $this->db;
    }
}
