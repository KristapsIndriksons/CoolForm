<?php

namespace CoolForm\Controller;

use CoolForm\DB\Repositories\UserRepository;
use CoolForm\Encoder\Password;
use CoolForm\Logger\Logger;
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
     * @var Logger
     */
    protected $logger;

    /**
     * IndexController constructor.
     */
    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->validator = new Login();
        $this->logger = new Logger();
        $this->execute();
    }

    private function execute()
    {
        if (!isset($_POST['submit'])) {
            return;
        }

        $formData = $this->getPostData();
        $this->validation($formData);

        $user = $this->findUser($formData['username'], $formData['password']);

        if (!$user) {
            $this->logger->error('Failed to find or register user', $formData);
            $this->returnToHomepage();
        }

        if (password_verify($formData['password'], $user->getPasswordHash())) {
            $this->userRepository->registerUserLogin($user);

            $_SESSION['username'] = $user->getUsername();
            $_SESSION['email'] = $user->getEmail();

            $this->redirectUser($user);
        } else {
            $this->logger->warning('Login with invalid password attempted', (array) $user);
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

    /**
     * @param User $user
     */
    private function redirectUser(User $user): void
    {
        if($user->getType() === 'admin') {
            $this->logger->info('Successful login of Admin user', (array) $user);
            header("Location: views/successAdmin.php");
        } else {
            $this->logger->info('Successful login of Normal user', (array) $user);
            header("Location: views/successUser.php");
        }
    }

    /**
     * @param array $data
     */
    private function validation(array $data) {
        if (!$this->validator->validateFormData($data)) {
            $this->logger->warning('Login attempted with invalid form data', $data);
            $this->returnToHomepage();
        };
    }

    private function returnToHomepage()
    {
        header("Location: index.html");
        exit();
    }
}
