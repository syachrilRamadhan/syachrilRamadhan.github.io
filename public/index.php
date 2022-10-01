<?php

require_once __DIR__ . '/../vendor/autoload.php';

use SyachrilRamadhan\StudiKasus\Crud\App\Router;
use SyachrilRamadhan\StudiKasus\Crud\Config\Database;
use SyachrilRamadhan\StudiKasus\Crud\Controller\HomeController;
use SyachrilRamadhan\StudiKasus\Crud\Controller\UserController;
use SyachrilRamadhan\StudiKasus\Crud\Middleware\MustNotLoginMiddleware;
use SyachrilRamadhan\StudiKasus\Crud\Middleware\MustLoginMiddleware;

Database::getConnection('prod');

// Home Controller
Router::add('GET', '/', HomeController::class, 'index', []);

// User Controller
Router::add('GET', '/users/register', UserController::class, 'register', [MustNotLoginMiddleware::class]);
Router::add('POST', '/users/register', UserController::class, 'postRegister', [MustNotLoginMiddleware::class]);
Router::add('GET', '/users/login', UserController::class, 'login', [MustNotLoginMiddleware::class]);
Router::add('POST', '/users/login', UserController::class, 'postLogin', [MustNotLoginMiddleware::class]);
Router::add('GET', '/users/logout', UserController::class, 'logout', [MustLoginMiddleware::class]);
Router::add('GET', '/users/profile', UserController::class, 'updateProfile', [MustLoginMiddleware::class]);
Router::add('POST', '/users/profile', UserController::class, 'postUpdateProfile', [MustLoginMiddleware::class]);
Router::add('GET', '/users/password', UserController::class, 'updatePassword', [MustLoginMiddleware::class]);
Router::add('POST', '/users/password', UserController::class, 'postUpdatePassword', [MustLoginMiddleware::class]);

Router::run();