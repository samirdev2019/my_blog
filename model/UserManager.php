<?php
class UserManager extends Database{

    private $pdo;

    public function __construct(){
        $this->pdo = $this->getPDO();
    }
    
    public function ChekIxistsUserName(string $username){
        $req=$this->pdo->prepare('SELECT user_id FROM users WHERE username=?'); 

       $req->execute([$username]); 
         $user = $req->fetch();
        return $user;
    } 
    public function ChekIxistsEmail(string $email){
        $req=$this->pdo->prepare('SELECT user_id FROM users WHERE email=?'); 

       $req->execute([$email]); 
         $email = $req->fetch();
        return $email;
    }   

    public function addUser(string $username,string $password, string $email):void{
        $user=$this->pdo->prepare('INSERT INTO users (username,password,email) VALUES(:username,:password,:email)');
         $user->execute([
            ':username'=>$username,
            ':password' => $password,
            ':email' => $email
        ]); 
        //return $this->pdo->lastInsertId ();
         
    }

    public function getUser(string $email):object{
        $req=$this->pdo->prepare('SELECT * FROM users WHERE email=?');
        $req->execute([$email]);
        return $user=$req->fetch(PDO::FETCH_OBJ);
    }
    public function getUsersNotYetValidated(){
        $req=$this->pdo->query('SELECT * FROM users WHERE validated is null');
        $req->execute();
         return $users=$req->fetchAll(PDO::FETCH_OBJ);
        
        
    }
}
