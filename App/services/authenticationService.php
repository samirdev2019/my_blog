<?php
namespace App\services;

use App\models\UserManager as UserManager;

class AuthenticationService
{
    private $userManager;

    /**
     * __construct assign the new object to attribute userManager
     *
     * @return void
     */
    public function __construct()
    {
        $this->userManager = new UserManager();
    }

    /**
     * The checkConnection function check user access right
     *  if user is registred and validated a seesion will start else
     * return an array of errors type
     *
     * @param string $email    of user
     * @param string $password of user
     *
     * @return array $verify
     */
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
    /**
     * The iSConnected verify if the user is connected
     *
     * @return bool
     */
    public function iSConnected():bool
    {
        if ($_SESSION['user_id']??0 != 0) {
            return true;
        }
        return false;
    }
}
