<?php
session_start();
require_once '../vendor/autoload.php';
use App\controllers\PostController as PostController;
use App\controllers\CommentController as CommentController;
use App\controllers\ContactController as ContactController;
use App\controllers\RegisterController as RegisterController;
use App\controllers\LoginController as LoginController;
use App\controllers\ValidateUsersController as ValidateUsersController;
use App\services\AuthenticationService as AuthenticationService;

$loader = new Twig_Loader_Filesystem(
    ['../App/views','../App/views/backend','../App/controllers']
);
$twig = new Twig_Environment(
    $loader,
    ['cache' => false]
);
$page = 'home';

if (isset($_GET['p'])) {
    $page = $_GET['p'];
} elseif (isset($_POST['p'])) {
    $page = $_POST['p'];
}
switch ($page) {
    case 'home':
        $connect = new AuthenticationService();
        if (!$connect->isConnected()) {
            echo $twig->render('home.twig', ['title' => 'Page d\'acueil']);
        } else {
            echo $twig->render(
                'home.twig',
                ['title' => 'Page d\'acueil','session'=>$_SESSION]
            );
        }
        break;
    case 'posts':
        $connect = new AuthenticationService();
        if (!$connect->isConnected()) {
            $getPosts = new PostController();
            echo $twig->render(
                'posts.twig',
                ['title' => 'Articles','posts' => $getPosts->getListPosts()]
            );
        } else {
            $getPosts = new PostController();
            echo $twig->render(
                'posts.twig',
                ['title' => 'Articles',
                'posts' => $getPosts->getListPosts(),
                'session' => $_SESSION]
            );
        }
        break;
    case 'post':
        if (isset($_GET['id']) || isset($_POST['id'])) {
            if (isset($_GET['id'])) {
                $id = (int) $_GET['id'];
            } else {
                $id = (int) $_POST['id'];
            }
            if ($id>0) {
                $getPosts = new PostController();
                $comments = new CommentController();
                if (!isset($_POST['comment']) && !isset($_POST['username'])) {
                    $succes = false;
                }
                if (!empty($_POST['comment']) && !empty($_POST['username'])) {
                    $comments->
                    addComment($_POST['id'], $_POST['username'], $_POST['comment']);
                    $succes = true;
                } else {
                    $succes = false;
                }
                $connect = new AuthenticationService();
                if (!$connect->isConnected()) {
                    echo $twig->render(
                        'Post.twig',
                        ['title' => 'détail d\'article',
                        'post' => $getPosts->getPost($id),
                        'comments' => $comments->getComments($id),
                        'succes' => $succes,
                        ]
                    );
                } else {
                    echo $twig->render(
                        'Post.twig',
                        [
                        'title' => 'détail d\'article',
                        'post' => $getPosts->getPost($id),
                        'comments' => $comments->getComments($id),
                        'session' => $_SESSION,
                        'succes' => $succes,
                        ]
                    );
                }
            } else {
                require 'index.php?p=posts';//à changer avec header()
            }
        }
        break;
    case 'managePost':
        $connect = new AuthenticationService();
        if ($connect->isConnected()) {
            if (!empty($_GET['action'])) {
                $post = new PostController();
                if (empty($_GET['id'])) {
                    $post->actionToDo($_GET['action']);
                } else {
                    $post->actionToDo($_GET['action'], $_GET['id']);
                }
            } else {
                header('HTTP/1.0 404 Not Found');
                echo $twig->render('404.twig');
            }
        } else {
            echo $twig->render('home.twig', ['title' => 'home']);
        }
        break;
    case 'updatePost':
        $connect = new AuthenticationService();
        if ($connect->isConnected()) {
            $post = new PostController();
            $post->managePost('UpdatePost', 'votre article a été bien mis à jour');
        }
        break;
    case 'commentsToValidate':
        $connect = new AuthenticationService();
        if ($connect->isConnected()) {
            $commentToValidat = new CommentController();
            if (isset($_GET['id'])) {
                $id = (int) $_GET['id'];
                if ($id>0) {
                    $comments=$commentToValidat->getInvalidComments($id);
                    echo $twig->render(
                        'invalidComments.twig',
                        ['comments' => $comments,
                        'message' =>'liste de commentaires pas encore validés']
                    );
                } else {
                    header('HTTP/1.0 404 Not Found');
                    echo $twig->render('404.twig');
                }
            } elseif (isset($_GET['id_comment'])&& isset($_GET['post_id'])) {
                $id_comment= (int) $_GET['id_comment'];
                $post_id= (int) $_GET['post_id'];
                $userId = $_SESSION['user_id'];
                
                if ($id_comment>0 && $post_id>0) {
                    $commentToValidat->validateComment($id_comment, $userId);
                    echo $twig->render(
                        'invalidComments.twig',
                        [
                        'comments' => $commentToValidat->
                        getInvalidComments($post_id),
                        'message' =>'liste de commentaires pas encore validés',
                        'session' => $_SESSION
                        ]
                    );
                } else {
                    header('HTTP/1.0 404 Not Found');
                    echo $twig->render('404.twig');
                }
            }
        } else {
            header('HTTP/1.0 404 Not Found');
            echo $twig->render('404.twig');
        }
        break;
    case 'connexion':
        if (empty($_POST)) {
            echo $twig->render(
                'login.twig',
                [
                'title' => 'Connexion',
                'message' => "identifiez-vous s'il vous plaît !",
                'type' => 'info'
                ]
            );
        }
        $verifyUser = new LoginController($_POST);
        $login = $verifyUser->checkFormLoginInformation();
        if ($login['info'] && $login['validated']) {
            echo $twig->render(
                'account.twig',
                [
                'title' => 'account',
                'session'=> $_SESSION,
                'message' => 'bienvenue dans votre compte'
                ]
            );
        } elseif ($login['info'] && !$login['validated']) {
            echo $twig->render(
                'login.twig',
                [
                'title' => 'Connexion',
                'message' => "votre compte n'est pas encore validée par un administrateur",
                'type' => 'info'
                ]
            );
        } else {
            echo $twig->render(
                'login.twig',
                [
                'title' => 'Connexion',
                'message' => "mauvais email ou mot de passe",
                'type' => 'danger'
                ]
            );
        }
        break;
    case 'backToAcount':
        $connect = new AuthenticationService();
        if ($connect->isConnected()) {
            echo $twig->render(
                'account.twig',
                [
                'title' => 'account',
                'session'=> $_SESSION,
                'message' => 'Bienvenue dans votre compte'
                ]
            );
        } else {
            echo $twig->render('home.twig', ['title'=>'Accueille']);
        }
        break;
    case 'logout':
        $verifyUser = new LoginController($_POST);
        $verifyUser->logOut();
        echo $twig->render('home.twig', ['title' => 'page d\'accueil']);
        break;
    case 'register':
        if (empty($_POST)) {
            echo $twig->render('register.twig', ['title'=>'inscription']);
            exit;
        }
            $register = new RegisterController($_POST);
            $errors=$register->chekInformationPresence();
        if (!empty($errors)) {
            echo $twig->render(
                'register.twig',
                ['title'=>'inscription', 'errors' => $errors]
            );
        } else {
            $password=$register->getHashPassword($_POST['password']);
            $register->addUser($_POST['username'], $password, $_POST['email']);
            echo $twig->render(
                'register.twig',
                ['title'=>'inscription', 'success' => 'vous etes bien enregistré']
            );
        }
        break;
    case 'manageUser':
        $connect = new AuthenticationService();
        if ($connect->isConnected()) {
            $usersNotValidate = new ValidateUsersController();
            if (isset($_GET['id'])) {
                $usersNotValidate->validateUser($_GET['id']);
                $message = "l'utilisateur a bien été validé";
            } else {
                $message = "après la validation d\'un utilisateur inscrit, il devient administrateur";
            }
            $users=$usersNotValidate->getUsersToValidate();
            echo $twig->render(
                'usersToValidate.twig',
                [
                'title' => 'utilsateurs inscrit',
                'users' => $users,
                'session'=>$_SESSION,
                'message'=>$message,
                'type'=>'info'
                ]
            );
        } else {
            echo $twig->render('home.twig', ['title' => 'accueille']);
        }
        break;
    case 'contact':
        $mail = new ContactController($_POST);
        $mail->chekName($_POST['username']);
        $mail->chekEmail($_POST['email']);
        $mail->chekMessage($_POST['message']);
        unset($faildes);
        $faildes = $mail->getFaildes();
        $connect = new AuthenticationService();
        if (!$connect->isConnected()) {
            $session = false;
        } else {
            $session = true;
        }
        if (isset($faildes) && !empty($faildes)) {
            echo $twig->render(
                'ContactForm.twig',
                [
                'errors' => $mail->getFaildes(),
                'username'=> $_POST['username'],
                'email' => $_POST['email'],
                'message' => $_POST['message'],
                'session' => $session
                ]
            );
        } else {
            if ($mail->sendMail()) {
                echo $twig->render(
                    'ContactForm.twig',
                    [
                    'succes' => 'votre mail a été bien envoyé!',
                    'session' => $session
                    ]
                );
            }
        }
        
        break;
    default:
        header('HTTP/1.0 404 Not Found');
        echo $twig->render('404.twig');
        break;
}
