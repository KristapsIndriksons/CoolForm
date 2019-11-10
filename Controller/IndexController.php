<?php

namespace CoolForm\Controller;

use CoolForm\DB\Repositories\UserRepository;
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
            header("Location: index.html");
            exit();
        };

        $user = $this->userRepository->getUser($formData['username']);

        if (!$user) {
            $user = $this->userRepository->registerUser($formData['username'], $formData['password']);
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
}
