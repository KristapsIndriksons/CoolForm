<?php

namespace CoolForm\Controller;

use CoolForm\DB\Repositories\UserRepository;
use CoolForm\Encoder\Password;
use CoolForm\Models\User;
use CoolForm\Validation\Login;

class IndexController
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var Login
     */
    protected $validator;

    /**
     * IndexController constructor.
     */
    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->validator = new Login();
        $this->execute();
    }

    private function execute()
    {
        if (!isset($_POST['submit'])) {
            return;
        }

        $formData = $this->getPostData();

        if (!$this->validator->validateFormData($formData)) {
            // Form data was invalid
            $this->returnToHomepage();
        };

        $user = $this->findUser($formData['username'], $formData['password']);

        if (!$user) {
            // Failed to find or register user
            $this->returnToHomepage();
        }

        if (password_verify($formData['password'], $user->getPasswordHash())) {
            $this->userRepository->registerUserLogin($user);

            $_SESSION['username'] = $user->getUsername();
            $_SESSION['email'] = $user->getEmail();

            if($user->getType() === 'admin') {
                header("Location: views/successAdmin.php");
            } else {
                header("Location: views/successUser.php");
            }
        } else {
            header("Location: index.html");
        }
    }

    /**
     * @return array
     */
    private function getPostData(): array
    {
        return [
            'username' => $_POST['username'] ?? null,
            'email' => $_POST['email'] ?? null,
            'password' => $_POST['password'] ?? null
        ];
    }

    /**
     * @param string $username
     * @param string $password
     * @return User
     */
    private function findUser(string $username, string $password): User
    {
        $user = $this->userRepository->getUser($username);

        if (!$user) {
            $user = $this->userRepository->registerUser($username, Password::encode($password));
        }

        return $user;
    }

    private function returnToHomepage()
    {
        header("Location: index.html");
        exit();
    }
}
