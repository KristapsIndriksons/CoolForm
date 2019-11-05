<?php

namespace CoolForm;

use CoolForm\DB\Repositories\UserRepository;

class IndexController
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * IndexController constructor.
     */
    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->execute();
    }

    private function execute()
    {
        $test = $this->userRepository;
    }
}
