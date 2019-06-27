<?php
session_start();
require_once '../vendor/autoload.php';
require '../App/autoloader.php';
require '../controllers/PostControler.php';
require '../controllers/CommentController.php';
require '../controllers/ContactController.php';
require '../controllers/RegisterController.php';
require '../controllers/LoginController.php';
require '../controllers/ValidateUsersController.php';

$loader = new Twig_Loader_Filesystem(['../views','../views/backend','../controllers']); 
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
        
        $getPosts = new PostControler();
        echo $twig->render('posts.twig',[
            'title' => 'Articles',
            'posts' => $getPosts->getListPosts()     
        ]);
        break;

        
    case 'post':
        if(isset($_GET['id']) || isset($_POST['id'])){
            if(isset($_GET['id'])){
                $id = (int) $_GET['id'];
            }else{
                $id = (int) $_POST['id'];
            }
            if($id>0){
                $getPosts = new PostControler();
                $comment = new CommentController();
                if(!empty($_POST['comment'])){
                    $comment->addComment($_POST['id'],$_POST['userId'],$_POST['comment']);  
                }
                echo $twig->render('Post.twig',[
                'title' => 'détail d\'article',
                'post' => $getPosts->getPost($id),
                'comments' => $comment->getComments($id)
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
                $post = new PostControler();
                if(empty($_GET['id'])){
                    $post->actionToDo($_GET['action']);
                }else{
                $post->actionToDo($_GET['action'],$_GET['id']);
                }
            }else{
                die('manque d action à faire');
            }
        }else{
            echo $twig->render('home.twig',[
                'title' => 'home',   
            ]);
        }
        break;  
    case 'updatePost':
        $connect = new AuthenticationService();
        if($connect->isConnected()){
            $post = new PostControler();
            $post->managePost('UpdatePost','votre article a été bien mis à jour');
        }
        break;
    case 'connexion' :
        // var_dump($_POST); die;
        if(empty($_POST)){
            echo $twig->render('login.twig',[
                'title' => 'Connexion',
                'message' => "identifiez-vous s'il vous plaît !",
                'type' => 'info'
                ]);
            break;
        }
        $verifyUser = new LoginController($_POST);
        $login = $verifyUser->checkFormLoginInformation();
        if($login['info'] && $login['validated'] ){
            echo $twig->render('account.twig',[
                'title' => 'account',
                'session'=> $_SESSION,
                'message' => 'bienvenue dans votre compte'
                ]);
                break;
            
        }elseif($login['info'] && !$login['validated']){
            echo $twig->render('login.twig',[
                'title' => 'Connexion',
                'message' => "votre compte n'est pas encore validée par un administrateur",
                'type' => 'info'
                ]);
                break;
        }else{
            echo $twig->render('login.twig',[
                'title' => 'Connexion',
                'message' => "mauvais email ou mot de passe",
                'type' => 'danger'
                ]);
                break;
        }
    case 'logout':
            $verifyUser = new LoginController($_POST);
            $verifyUser->logOut();
            echo $twig->render('home.twig',[
                'title' => 'page d\'accueil',
                ]);
                break;
            break;
    case 'register' : 
            if(empty($_POST)){
                echo $twig->render('register.twig',[
                    'title'=>'inscription',
                    ]); 
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
        $users = new ValidateUsers();
       $usersNotValidate= $users->getUsersToValidate();
        
        echo $twig->render('usersToValidate.twig',[
            'title' => 'utilsateurs inscrit',
            'users' => $users,
            'session'=>$_SESSION,
            'message'=>'après la validation d\'un utilisateur inscrit, il devient administrateur',
            'tyep'=>'info'
        ]);
    break;
    case 'contact' :
            unset($faildes);
            $mail= new ControllerContact($_POST);
            $mail->chekName($_POST['username']);
            $mail->chekEmail($_POST['email']);
            $mail->chekMessage($_POST['message']);
            $faildes = $mail->getFaildes();
            if(isset($faildes) && !empty($faildes)){
                echo $twig->render('ContactForm.twig',[
                'errors' => $mail->getFaildes(),
                'username'=> $_POST['username'],
                'email' => $_POST['email'],
                'message' => $_POST['message']
                ]);
                break;  
            }else{
                if($mail->sendMail()){
                    echo $twig->render('ContactForm.twig',['succes' => 'votre mail a été bien envoyé!']);
                    break; 
                }
                 
            }
                       
        
    default :
        header('HTTP/1.0 404 Not Found');
        echo $twig->render('404.twig');
        break;
}




    

        
