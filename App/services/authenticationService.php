<?php

namespace App\services;

use App\models\UserManager as UserManager;

class AuthenticationService
{
    private $userManager;

    public function __construct()
    {
        $this->userManager = new UserManager();
    }

    public function checkConnection(string $email, string $password):array
    {
        $user=$this->userManager->getUser($email);
        if ($user && password_verify($password, $user->password)) {
            if ($user->validated === null) {
                $verify['validated'] =false;
                $verify['info']= true;
            } else {
                $verify['validated'] = true;
                $verify['info'] = true;
                $_SESSION['user_id']=$user->user_id;
                $_SESSION['username'] = $user->username;
                $_SESSION['password'] = $user->password;
                $_SESSION['email'] = $user->email;
            }
        } else {
            $verify['info'] = false;
            $verify['validated'] = false;
        }
        return $verify;
    }
    public function iSConnected():bool
    {
        if ($_SESSION['user_id']??0 != 0) {
            return true;
        }
        return false;
    }
}
