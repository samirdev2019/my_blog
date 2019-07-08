<?php
namespace App\controllers;

use App\models\UserManager as UserManager;

class ValidateUsersController
{
    private $userManager;
    public function __construct()
    {
        $this->userManager = new UserManager();
    }
    public function getUsersToValidate():array
    {
        return $users= $this->userManager->getUsersNotYetValidated();
    }
    public function validateUser(int $id_user):bool
    {
        return $this->userManager->ValidateUser($id_user);
    }
}
