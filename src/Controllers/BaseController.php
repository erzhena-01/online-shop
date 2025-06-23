<?php

namespace Controllers;

use Model\User;

class BaseController
{
    private User $userModel;
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

   public function auth( string $email, string $password): bool
   {

           $user = $this->userModel->getByEmail($email);

           if (!$user) {
              return false;
           } else {
               $passwordDB = $user->getPassword();

               if (password_verify($password , $passwordDB)) {
                   $this->startSession();
                   $_SESSION['user_id'] = $user->getId();

                   return true;
               } else {
                   return false;
               }
           }

   }

    private function startSession()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
}