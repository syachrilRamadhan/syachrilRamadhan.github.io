<?php

namespace SyachrilRamadhan\StudiKasus\Crud\Middleware {

    require_once __DIR__ . '/../Helper/helper.php';

    use PHPUnit\Framework\TestCase;
    use SyachrilRamadhan\StudiKasus\Crud\Config\Database;
    use SyachrilRamadhan\StudiKasus\Crud\Domain\Session;
    use SyachrilRamadhan\StudiKasus\Crud\Domain\User;
    use SyachrilRamadhan\StudiKasus\Crud\Repository\SessionRepository;
    use SyachrilRamadhan\StudiKasus\Crud\Repository\UserRepository;
    use SyachrilRamadhan\StudiKasus\Crud\Service\SessionService;

    class MustLoginMiddlewareTest extends TestCase
    {

        private MustLoginMiddleware $middleware;
        private UserRepository $userRepository;
        private SessionRepository $sessionRepository;

        protected function setUp():void
        {
            $this->middleware = new MustLoginMiddleware();
            putenv("mode=test");

            $this->userRepository = new UserRepository(Database::getConnection());
            $this->sessionRepository = new SessionRepository(Database::getConnection());

            $this->sessionRepository->deleteAll();
            $this->userRepository->deleteAll();
        }

        public function testBeforeGuest()
        {
            $this->middleware->before();
            $this->expectOutputRegex("[Location: /users/login]");
        }

        public function testBeforeLoginUser()
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

            $this->middleware->before();
            $this->expectOutputString("");
        }

    }
}