<?php

namespace SyachrilRamadhan\StudiKasus\Crud\Service;

use SyachrilRamadhan\StudiKasus\Crud\Config\Database;
use SyachrilRamadhan\StudiKasus\Crud\Domain\User;
use SyachrilRamadhan\StudiKasus\Crud\Exception\ValidationException;
use SyachrilRamadhan\StudiKasus\Crud\Model\UserLoginRequest;
use SyachrilRamadhan\StudiKasus\Crud\Model\UserLoginResponse;
use SyachrilRamadhan\StudiKasus\Crud\Model\UserPasswordUpdateRequest;
use SyachrilRamadhan\StudiKasus\Crud\Model\UserPasswordUpdateResponse;
use SyachrilRamadhan\StudiKasus\Crud\Model\UserProfileUpdateRequest;
use SyachrilRamadhan\StudiKasus\Crud\Model\UserProfileUpdateResponse;
use SyachrilRamadhan\StudiKasus\Crud\Model\UserRegisterRequest;
use SyachrilRamadhan\StudiKasus\Crud\Model\UserRegisterResponse;
use SyachrilRamadhan\StudiKasus\Crud\Repository\UserRepository;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(UserRegisterRequest $request): UserRegisterResponse
    {
        $this->validateUserRegistrationRequest($request);

        try {
            Database::beginTransaction();
            $user = $this->userRepository->findById($request->id);
            if ($user != null) {
                throw new ValidationException("User Id Sudah Ada");
            }

            $user = new User();
            $user->id = $request->id;
            $user->name = $request->name;
            $user->password = password_hash($request->password, PASSWORD_BCRYPT);

            $this->userRepository->save($user);

            $response = new UserRegisterResponse();
            $response->user = $user;

            Database::commitTransaction();
            return $response;
        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    private function validateUserRegistrationRequest(UserRegisterRequest $request)
    {
        if ($request->id == null || $request->name == null || $request->password == null ||
            trim($request->id) == "" || trim($request->name) == "" || trim($request->password) == "") {
            throw new ValidationException("Id, Name, Password Tidak Boleh Kosong!");
        }
    }

    public function login(UserLoginRequest $request): UserLoginResponse
    {
        $this->validateUserLoginRequest($request);

        $user = $this->userRepository->findById($request->id);
        if ($user == null) {
            throw new ValidationException("Id atau password salah");
        }

        if (password_verify($request->password, $user->password)) {
            $response = new UserLoginResponse();
            $response->user = $user;
            return $response;
        } else {
            throw new ValidationException("Id atau password salah");
        }
    }

    private function validateUserLoginRequest(UserLoginRequest $request)
    {
        if ($request->id == null || $request->password == null ||
            trim($request->id) == "" || trim($request->password) == "") {
            throw new ValidationException("Id, Password Tidak Boleh Kosong!");
        }
    }

    public function updateProfile(UserProfileUpdateRequest $request): UserProfileUpdateResponse
    {
        $this->validateUserProfileUpdateRequest($request);

        try {
            Database::beginTransaction();

            $user = $this->userRepository->findById($request->id);
            if ($user == null) {
                throw new ValidationException("User id Tidak Ditemukan");
            }

            $user->name = $request->name;
            $this->userRepository->update($user);

            Database::commitTransaction();

            $response = new UserProfileUpdateResponse();
            $response->user = $user;
            return $response;
        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    private function validateUserProfileUpdateRequest(UserProfileUpdateRequest $request)
    {
        if ($request->id == null || $request->name == null ||
            trim($request->id) == "" || trim($request->name) == "") {
            throw new ValidationException("Id, Name tidak boleh kosong");
        }
    }

    public function updatePassword(UserPasswordUpdateRequest $request): UserPasswordUpdateResponse
    {
        $this->validateUserPasswordUpdateRequest($request);

        try {
            Database::beginTransaction();

            $user = $this->userRepository->findById($request->id);
            if ($user == null) {
                throw new ValidationException("User id tidak ditemukan");
            }

            if (!password_verify($request->oldPassword, $user->password)) {
                throw new ValidationException("Password lama anda salah");
            }

            $user->password = password_hash($request->newPassword, PASSWORD_BCRYPT);
            $this->userRepository->update($user);

            Database::commitTransaction();

            $response = new UserPasswordUpdateResponse();
            $response->user = $user;
            return $response;
        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    private function validateUserPasswordUpdateRequest(UserPasswordUpdateRequest $request)
    {
        if ($request->id == null || $request->oldPassword == null || $request->newPassword == null ||
            trim($request->id) == "" || trim($request->oldPassword) == "" || trim($request->newPassword) == "") {
            throw new ValidationException("Id, Password lama, Password baru tidak boleh kosong");
        }
    }
}