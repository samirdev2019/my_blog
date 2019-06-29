<?php
require '../services/authenticationService.php';

class LoginController
{
    private $datas = [];
    private $authenticationService; 
    public $verify=[];

    public function __construct(array $datas=[])
    {
        $this->datas = $datas;
        $this->authenticationService = new AuthenticationService();
    }

    public function checkFormLoginInformation():array
    {
        if(empty($this->datas['email']) || empty($this->datas['password'])){
            
            $verify['validated'] = false;
            $verify['info']= false;
            return $verify;
        }else{
            return $this->authenticationService
            ->checkConnection($this->datas['email'],
            $this->datas['password']);
        }
    }
    
    public function logOut()
    {
        $_SESSION = [];
        session_destroy();
    }
}
