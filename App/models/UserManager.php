<?php
namespace App\models;

use App\models\Database as Database;

class UserManager extends Database
{
    private $pdo;

    /**
     * __construct assign value of PDO object database connection to attribute $db
     *
     * @return void
     */
    public function __construct()
    {
        $this->pdo = $this->getPDO();
    }
    /**
     * The chekIxistsUserName checking if the username
     * has been used by another registration previously
     *
     * @param string $username username use in registration
     *
     * @return bool
     */
    public function chekIxistsUserName(string $username)
    {
        $req=$this->pdo->prepare('SELECT user_id FROM users WHERE username=?');

        $req->execute([$username]);
        return $req->fetch();
    }
    
    /**
     * The chekIxistsEmail checking if
     * the email has been used by another registration previously
     *
     * @param string $email email use in the form of registration
     *
     * @return void
     */
    public function chekIxistsEmail(string $email)
    {
        $req=$this->pdo->prepare('SELECT user_id FROM users WHERE email=?');

        $req->execute([$email]);
         $email = $req->fetch();
        return $email;
    }

    /**
     * The addUser method allow to register a new user in data base
     *
     * @param string $username of user
     * @param string $password of user
     * @param string $email    of user
     *
     * @return void
     */
    public function addUser(string $username, string $password, string $email):void
    {
        $user=$this->pdo->prepare(
            'INSERT INTO users (username,password,email,date_registration)
            VALUES(:username,:password,:email,now())'
        );
        $user->execute(
            [
            ':username'=>$username,
            ':password' => $password,
            ':email' => $email
            ]
        );
        
        //return $this->pdo->lastInsertId ();
    }

    /**
     * The getUser get user from data base
     *
     * @param string $email of user
     *
     * @return $user object
     */
    public function getUser(string $email)
    {
        $req=$this->pdo->prepare('SELECT * FROM users WHERE email=?');
        $req->execute([$email]);
        return $user=$req->fetch(\PDO::FETCH_OBJ);
    }

    /**
     * The getUsersNotYetValidated get invalidated users
     *
     * @return array $users not validated by an administrator
     */
    public function getUsersNotYetValidated():array
    {
        $req=$this->pdo->query(
            'SELECT user_id,username,email,
            date_format(date_registration,\' %d\%m\%Y %Hh%imin%ss\')
            as registred
            FROM users WHERE validated is null'
        );
        $req->execute();
        return $users=$req->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * The validateUser allow to validate an user updating the validated value
     *
     * @param int $id_user the user ID
     *
     * @return bool
     */
    public function validateUser(int $id_user):bool
    {
        $req=$this->pdo->prepare('UPDATE users SET validated=1 WHERE user_id=?');
        return $req->execute([$id_user]);
    }
}
