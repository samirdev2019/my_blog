<?php
namespace App\models;

use App\models\Database as Database;

class UserManager extends Database
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = $this->getPDO();
    }
    // ambiguité pour la type de valeur reteuner par la fonction
    public function chekIxistsUserName(string $username)
    {
        $req=$this->pdo->prepare('SELECT user_id FROM users WHERE username=?');

        $req->execute([$username]);
        return $req->fetch();
    }
    // ambiguité pour la type de valeur reteuner par la fonction
    public function chekIxistsEmail(string $email)
    {
        $req=$this->pdo->prepare('SELECT user_id FROM users WHERE email=?');

        $req->execute([$email]);
         $email = $req->fetch();
        return $email;
    }

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

    public function getUser(string $email)
    {
        $req=$this->pdo->prepare('SELECT * FROM users WHERE email=?');
        $req->execute([$email]);
        return $user=$req->fetch(\PDO::FETCH_OBJ);
    }

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

    public function validateUser(int $id_user):bool
    {
        $req=$this->pdo->prepare('UPDATE users SET validated=1 WHERE user_id=?');
        return $req->execute([$id_user]);
    }
}
