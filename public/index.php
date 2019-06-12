<?php
require_once '../vendor/autoload.php';
require '../controler/PostControler.php';

// routing
$loader = new Twig_Loader_Filesystem('../views'); 
$twig = new Twig_Environment($loader, [
    'cache' => false,//__DIR__ . '/tmp',
]);

$page = 'home';
if(isset($_GET['p'])){
    $page = $_GET['p'];
}
switch($page){
    case 'home' :
        echo $twig->render('home.twig',['title' => 'Page d\'acueil']);
        break;
    case 'contact' :
        echo $twig->render('contact.twig',['title' => 'Page contact']);
        break;
    case 'posts' :
        $getPosts = new PostControler;
        echo $twig->render('posts.twig',[
            'title' => 'Articles',
            'posts' => $getPosts->getListPosts()
        
        
        ]);
        break;
    case 'postdetail':
            echo $twig->render('viewPost.twig',['title' => 'détail d\'article']);
            break;
    
    case 'connexion' :
        echo $twig->render('login.twig',['title' => 'Connexion']);
        break;
    case 'home#about' :
        echo $twig->render('cv.twig',['title' => 'Qui suis-je']);
        break;
    default :
        header('HTTP/1.0 404 Not Found');
        echo $twig->render('404.twig');
        break;
}




    

        
