<?php
namespace App\controllers;

use App\models\UserManager as UserManager;

class ValidateUsersController
{
    private $userManager;
    /**
     * __construct initialization of usermanager object
     *
     * @return void
     */
    public function __construct()
    {
        $this->userManager = new UserManager();
    }
    /**
     * The getUsersToValidate function call userManger and requeste the
     * users list not yet validated,
     *
     * @return array $users list of users waiting validation
     */
    public function getUsersToValidate():array
    {
        return $users= $this->userManager->getUsersNotYetValidated();
    }
    /**
     * The validateUser function call a model userManager for verfy if user
     * is validated or no
     *
     * @param int $id_user user ID
     *
     * @return bool
     */
    public function validateUser(int $id_user):bool
    {
        return $this->userManager->ValidateUser($id_user);
    }
}
