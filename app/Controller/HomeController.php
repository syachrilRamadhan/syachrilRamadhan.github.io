<?php

namespace SyachrilRamadhan\StudiKasus\Crud\Controller;

use SyachrilRamadhan\StudiKasus\Crud\App\View;
use SyachrilRamadhan\StudiKasus\Crud\Config\Database;
use SyachrilRamadhan\StudiKasus\Crud\Repository\SessionRepository;
use SyachrilRamadhan\StudiKasus\Crud\Repository\UserRepository;
use SyachrilRamadhan\StudiKasus\Crud\Service\SessionService;

class HomeController
{

    private SessionService $sessionService;

    public function __construct()
    {
        $connection = Database::getConnection();
        $sessionRepository = new SessionRepository($connection);
        $userRepository = new UserRepository($connection);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }


    function index()
    {
        $user = $this->sessionService->current();
        if ($user == null) {
            View::render('Home/index', [
                "title" => "Login Crud"
            ]);
        } else {
            View::render('Home/dashboard', [
                "title" => "Dashboard",
                "user" => [
                    "name" => $user->name
                ]
            ]);
        }
    }

}