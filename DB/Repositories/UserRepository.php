<?php

namespace CoolForm\DB\Repositories;

use CoolForm\DB\Connector;
use CoolForm\Encoder\Password;
use CoolForm\Models\User;
use PDO;

/**
 * Class UserRepository
 * Fetch user data from DB
 *
 * @package CoolForm
 */
class UserRepository
{
    const USER_TABLE = 'users';
    const USER_LOGIN_HISTORY_TABLE = 'user_login_history';

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
     * @return User|null
     */
    public function getUser(string $username): ?User
    {
        $pdo = $this->db->getPDO();
        $query = sprintf('SELECT * FROM %s WHERE `username` = :username', self::USER_TABLE);

        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':username', $username);
        $stmt->execute();
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$userData) {
            return null;
        }

        $user = new User();
        $user->setId($userData['user_ID']);
        $user->setUsername($userData['username']);
        $user->setPasswordHash($userData['password']);
        $user->setEmail($userData['email'] ?? '');
        $user->setType($userData['user_type']);

        return $user;
    }

    /**
     * @param string $username
     * @param string $passwordHash
     * @param string|null $type
     * @param null $email
     * @return User|null
     */
    public function registerUser(string $username, string $passwordHash, string $type = 'user', $email = null): ?User
    {
        $pdo = $this->db->getPDO();
        $query = sprintf(
            'INSERT INTO %s (user_ID, username, password, user_type, email) ' .
            'VALUES (null, :username, :password, :user_type, :email)',
            self::USER_TABLE
        );

        $pdo->prepare($query)->execute([
            'username' => $username,
            'password' => Password::encode($passwordHash),
            'user_type' => $type,
            'email' => $email
        ]);

        return $this->getUser($username);
    }

    /**
     * @param User $user
     * @return bool
     */
    public function registerUserLogin(User $user): bool
    {
        $pdo = $this->db->getPDO();
        $query = sprintf(
            'INSERT INTO %s (id, date, IP) ' .
            'VALUES (:id, null, :ip)',
            self::USER_LOGIN_HISTORY_TABLE
        );

        return $pdo->prepare($query)->execute([
            'id' => $user->getId(),
            'ip' => $this->getUsersIP(),
        ]);
    }

    /**
     * @return string|null
     */
    private function getUsersIP(): ?string
    {
        $ip = null;

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }
}
