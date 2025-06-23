<?php

namespace Service;

use Model\User;
class AuthService
{

    protected User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function check(): bool
    {
        $this->startSession();
        return isset($_SESSION['user_id']);
    }

    public function getCurrentUserId(): ?int
    {
        $this->startSession();

        if ($this->check()) {
            return $_SESSION['user_id'];
        } else {
            return null;
        }

    }

    public function auth(string $email, string $password): bool
    {

        $user = $this->userModel->getByEmail($email);

        if (!$user) {
            return false;
        } else {
            $passwordDB = $user->getPassword();

            if (password_verify($password, $passwordDB)) {
                $this->startSession();
                $_SESSION['user_id'] = $user->getId();

                return true;
            } else {
                return false;
            }
        }

    }

    public function logout()
    {
        $this->startSession();
        session_destroy();
    }

    private function startSession()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

}