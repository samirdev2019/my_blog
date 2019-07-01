<?php
namespace controllers;

use models\UserManager as UserManager;
class ValidateUsersController
{
    private $userManager;
    public function __construct()
    {
        $this->userManager = new UserManager();
    }
    function getUsersToValidate():array{
       return $users= $this->userManager->getUsersNotYetValidated();
       
    }

    public function validateUser(int $id_user):bool
    {
       return $this->userManager->ValidateUser($id_user);
    }
}
