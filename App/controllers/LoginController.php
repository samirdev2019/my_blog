<?php
namespace App\controllers;

use App\services\AuthenticationService as AuthenticationService;

class LoginController
{
    private $datas = [];
    private $authenticationService;
    public $verify=[];

    /**
     * __construct
     *
     * @param array $datas the dataset comes from the login form sent by the user
     *
     * @return void
     */
    public function __construct(array $datas = [])
    {
        $this->datas = $datas;
        $this->authenticationService = new AuthenticationService();
    }
    /**
     * Allows to verify the expected information of the user,
     * depending on the case, it returns either a table indicator
     * or it calls another function to check connect
     *
     * @return array presence info in database and form
     */
    public function checkFormLoginInformation():array
    {
        if (empty($this->datas['email']) || empty($this->datas['password'])) {
            $verify['validated'] = false;
            $verify['info']= false;
            return $verify;
        } else {
            return $this->authenticationService
                ->checkConnection($this->datas['email'], $this->datas['password']);
        }
    }
    /**
     * The logOut method allow to logout the user
     *
     * @return void
     */
    public function logOut():void
    {
        $_SESSION = [];
        session_destroy();
    }
}
