<?php

namespace SyachrilRamadhan\StudiKasus\Crud\Controller;

use PHPUnit\Framework\TestCase;
use SyachrilRamadhan\StudiKasus\Crud\Config\Database;
use SyachrilRamadhan\StudiKasus\Crud\Domain\Session;
use SyachrilRamadhan\StudiKasus\Crud\Domain\User;
use SyachrilRamadhan\StudiKasus\Crud\Repository\SessionRepository;
use SyachrilRamadhan\StudiKasus\Crud\Repository\UserRepository;
use SyachrilRamadhan\StudiKasus\Crud\Service\SessionService;

class HomeControllerTest extends TestCase
{
    private HomeController $homeController;
    private UserRepository $userRepository;
    private SessionRepository $sessionRepository;

    protected function setUp():void
    {
        $this->homeController = new HomeController();
        $this->sessionRepository = new SessionRepository(Database::getConnection());
        $this->userRepository = new UserRepository(Database::getConnection());

        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAll();
    }

    public function testGuest()
    {
        $this->homeController->index();

        $this->expectOutputRegex("[Login Crud]");
    }

    public function testUserLogin()
    {
        $user = new User();
        $user->id = "rama";
        $user->name = "Rama";
        $user->password = "rahasia";
        $this->userRepository->save($user);

        $session = new Session();
        $session->id = uniqid();
        $session->userId = $user->id;
        $this->sessionRepository->save($session);

        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

        $this->homeController->index();

        $this->expectOutputRegex("[Hello Rama]");
    }

}