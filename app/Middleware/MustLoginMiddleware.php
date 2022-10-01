<?php

namespace SyachrilRamadhan\StudiKasus\Crud\Middleware;

use SyachrilRamadhan\StudiKasus\Crud\App\View;
use SyachrilRamadhan\StudiKasus\Crud\Config\Database;
use SyachrilRamadhan\StudiKasus\Crud\Repository\SessionRepository;
use SyachrilRamadhan\StudiKasus\Crud\Repository\UserRepository;
use SyachrilRamadhan\StudiKasus\Crud\Service\SessionService;

class MustLoginMiddleware implements Middleware
{
    private SessionService $sessionService;

    public function __construct()
    {
        $sessionRepository = new SessionRepository(Database::getConnection());
        $userRepository = new UserRepository(Database::getConnection());
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }

    function before(): void
    {
        $user = $this->sessionService->current();
        if ($user == null) {
            View::redirect('/users/login');
        }
    }
}