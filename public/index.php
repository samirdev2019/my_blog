<?php
session_start();
require_once '../vendor/autoload.php';
use controllers\PostController as PostController;
use controllers\CommentController as CommentController;
use controllers\ContactController as ContactController;
use controllers\RegisterController as RegisterController;
use controllers\LoginController as LoginController;
use controllers\ValidateUsersController as ValidateUsersController;
use services\AuthenticationService as AuthenticationService;
$loader = new Twig_Loader_Filesystem(['../App/views','../App/views/backend','../App/controllers']); 
$twig = new Twig_Environment($loader, [
    'cache' => false,//__DIR__ . '/tmp',
    ]);
$page = 'home';
if(isset($_GET['p'])){
    $page = $_GET['p'];
}elseif(isset($_POST['p'])){
    $page = $_POST['p'];
}
switch($page){
    case 'home' :
        echo $twig->render('home.twig',['title' => 'Page d\'acueil']);
    break;
    case 'posts' :
        $getPosts = new PostController();
        echo $twig->render('posts.twig',[
            'title' => 'Articles',
            'posts' => $getPosts->getListPosts()     
        ]);
    break; 
    case 'post':
        if(isset($_GET['id'])){
            if(isset($_GET['id'])){
                $id = (int) $_GET['id'];
            }
            if($id>0){
                $getPosts = new PostController();
                $comments = new CommentController();
                if(!empty($_POST['comment'])){
                    $comments->addComment($_POST['id'],$_POST['userId'],$_POST['comment']);  
                }
                echo $twig->render('Post.twig',[
                'title' => 'détail d\'article',
                'post' => $getPosts->getPost($id),
                'comments' => $comments->getComments($id)
                ]);
            }else{
                require 'index.php?p=posts';
            }
        }  
    break;
    case 'managePost':
        $connect = new AuthenticationService();
        if($connect->isConnected()){
            if(!empty($_GET['action'])){
                $post = new PostController();
                if(empty($_GET['id'])){
                    $post->actionToDo($_GET['action']);
                }else{
                $post->actionToDo($_GET['action'],$_GET['id']);
                }
            }else{
                header('HTTP/1.0 404 Not Found');
                echo $twig->render('404.twig');
            }
        }else{
            echo $twig->render('home.twig',['title' => 'home']);
        }
    break;  
    case 'updatePost':
        $connect = new AuthenticationService();
        if($connect->isConnected()){
            $post = new PostController();
            $post->managePost('UpdatePost','votre article a été bien mis à jour');
        }
    break;
    case 'commentsToValidate':
        $connect = new AuthenticationService();
        if($connect->isConnected()){
            $commentToValidat = new CommentController();
            if(isset($_GET['id'])){
                $id = (int) $_GET['id'];
                if($id>0){
                    $comments=$commentToValidat->getInvalidComments($id);
                    echo $twig->render('invalidComments.twig',[
                    'comments' => $comments,
                    'message' =>'liste de commentaires pas encore validés'
                    ]); 
                }else{
                    header('HTTP/1.0 404 Not Found');
                    echo $twig->render('404.twig');
                    //break;
                }
            }elseif(isset($_GET['id_comment'])&& isset($_GET['post_id'])){
                $id_comment= (int) $_GET['id_comment'];
                $post_id= (int) $_GET['post_id'];
                if($id_comment>0 && $post_id>0 ){
                    $commentToValidat->validateComment($id_comment);
                    echo $twig->render('invalidComments.twig',[
                    'comments' => $commentToValidat->getInvalidComments($post_id),
                    'message' =>'liste de commentaires pas encore validés'
                    ]); 
                }else{
                    header('HTTP/1.0 404 Not Found');
                    echo $twig->render('404.twig');
                    //break;
                } 
            }
        }else{
            header('HTTP/1.0 404 Not Found');
            echo $twig->render('404.twig');
            //break;
        }
    break; 
    case 'connexion' :
        if(empty($_POST)){
            echo $twig->render('login.twig',[
            'title' => 'Connexion',
            'message' => "identifiez-vous s'il vous plaît !",
            'type' => 'info'
            ]);
            //break;
        }
        $verifyUser = new LoginController($_POST);
        $login = $verifyUser->checkFormLoginInformation();
        if($login['info'] && $login['validated'] ){
            echo $twig->render('account.twig',[
            'title' => 'account',
            'session'=> $_SESSION,
            'message' => 'bienvenue dans votre compte'
            ]);
            //break;
        }elseif($login['info'] && !$login['validated']){
            echo $twig->render('login.twig',[
            'title' => 'Connexion',
            'message' => "votre compte n'est pas encore validée par un administrateur",
            'type' => 'info'
            ]);
            //break;
        }else{
            echo $twig->render('login.twig',[
            'title' => 'Connexion',
            'message' => "mauvais email ou mot de passe",
            'type' => 'danger'
            ]);
            //break;
        }
    break;
    case 'logout':
        $verifyUser = new LoginController($_POST);
        $verifyUser->logOut();
        echo $twig->render('home.twig',['title' => 'page d\'accueil']);
    break;
    case 'register' : 
            if(empty($_POST)){
                echo $twig->render('register.twig',['title'=>'inscription']); 
                exit;
            }
            $register = new RegisterController($_POST);
            $errors=$register->chekInformationPresence();
            if(!empty($errors)){
                echo $twig->render('register.twig',[
                'title'=>'inscription',
                'errors' => $errors
                ]);
            }else{
                
            $password=$register->getHashPassword($_POST['password']);
            $register->addUser($_POST['username'],$password,$_POST['email']);
            echo $twig->render('register.twig',['
            title'=>'inscription',
            'success' => 'vous etes bien enregistré'
            ]);
        }
    break;
    case 'manageUser':
        $connect = new AuthenticationService();
        if($connect->isConnected()){
            $usersNotValidate = new ValidateUsersController();
            if(isset($_GET['id'])){
                $usersNotValidate->validateUser($_GET['id']);
                $message = "l'utilisateur a bien été validé";
            }else{
                $message = "après la validation d\'un utilisateur inscrit, il devient administrateur";
            }
            $users=$usersNotValidate->getUsersToValidate();
            echo $twig->render('usersToValidate.twig',[
            'title' => 'utilsateurs inscrit',
            'users' => $users,
            'session'=>$_SESSION,
            'message'=>$message,
            'type'=>'info'
            ]);
        }else{
            echo $twig->render('home.twig',['title' => 'accueille']);
        }
    break;
    case 'contact':
        $mail = new ContactController($_POST);
        $mail->chekName($_POST['username']);
        $mail->chekEmail($_POST['email']);
        $mail->chekMessage($_POST['message']);
        unset($faildes);
        $faildes = $mail->getFaildes();
        if(isset($faildes) && !empty($faildes)){
            echo $twig->render('ContactForm.twig',[
            'errors' => $mail->getFaildes(),
            'username'=> $_POST['username'],
            'email' => $_POST['email'],
            'message' => $_POST['message']
            ]);
            //break;  
        }else{
            if($mail->sendMail()){
                echo $twig->render('ContactForm.twig',['succes' => 'votre mail a été bien envoyé!']);
                // break; 
            }     
        }             
    break;  
    default :
        header('HTTP/1.0 404 Not Found');
        echo $twig->render('404.twig');
    break;
}
