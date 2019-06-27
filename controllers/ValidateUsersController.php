<?php
class ValidateUsers extends LoginController{
    function getUsersToValidate(){
       return $users= $this->userManager->getUsersNotYetValidated();
       
    }
}