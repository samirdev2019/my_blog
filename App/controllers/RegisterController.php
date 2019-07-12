<?php
namespace App\controllers;

use App\models\UserManager as UserManager;

class RegisterController
{
    
    private $datas = [];
    private $userManager;
    
    public function __construct(array $datas = [])
    {
        $this->datas = $datas;
        $this->userManager = new UserManager();
    }
    /**
     * The chekInformationPresence : this function allow
     *  verification of all information that comes from the user
     * 1:Is the pseudonym requested by the visitor free?
     *  If it is already present in the database, you will have to ask
     *  the visitor to choose another one.
     * 2:Does the e-mail address have a valid form?and is not already used
     *  by another registration!
     * 3: Are the two passwords entered the same?
     *
     * @return array $errors: faildes that can be comitted by the user
     */
    public function chekInformationPresence():array
    {
        $errors=[];
        foreach ($this->datas as $key => $value) {
            if ($key === 'username') {
                if (empty($value) || !preg_match("#^[a-zA-z0-9]{6,}$#", $this->datas['username'])) {
                    $errors[] = "vous devez vérifier votre nom d'utilisateur";
                } else {
                    if ($this->userManager->ChekIxistsUserName($this->datas['username'])) {
                        $errors[] = "le nom d'utilisateur est déja pris";
                    }
                }
            }
            if (empty($value) && $key === 'password') {
                $errors[] = "mot de passe";
            }
            if ($key === 'pasword_confirmation') {
                if (empty($value) || $this->datas['password'] != $this->datas['pasword_confirmation']) {
                    $errors[] = "confirmation de mot de passe incorecte";
                }
            }
            if ($key === 'email') {
                if (empty($value) || !filter_var($this->datas['email'], FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "vous devez renseigner une adresse email correcte";
                } else {
                    if ($this->userManager->ChekIxistsEmail($this->datas['email'])) {
                        $errors[] = "cette adresse mail est déja utilisée";
                    }
                }
            }
        }
        return $errors;
    }
    /**
     * The getHashPassword :chop the password before storing it,
     *  so that it's no longer "readable".
     *
     * @param string $password the password
     *
     * @return string $paswordhashed : the password hashed (choped)
     */
    public function getHashPassword(string $password)
    {
        $paswordhashed = password_hash($this->datas['password'], PASSWORD_BCRYPT);
        return $paswordhashed;
    }
    /**
     * The addUser : allow us to add a new user calling
     * the function addUser of UserManager class
     *
     * @param string $username the username of user
     * @param string $password the pasword of user
     * @param string $email    the email of user
     *
     * @return void
     */
    public function addUser(string $username, string $password, string $email):void
    {
        $this->userManager->addUser($username, $password, $email);
    }
}
