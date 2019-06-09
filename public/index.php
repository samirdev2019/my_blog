<?php
require_once '../vendor/autoload.php';
// routing
$page = 'home';
if(isset($_GET['p'])){
    $page = $_GET['p'];
}
//rendu  du template
//require_once 'vendor/autoload.php';
// on va charger un nouveau loader on lui précisant le chemin où se trouve notre template

$loader = new Twig_Loader_Filesystem('../views'); 
//une fois le loader est chargé on peut initialiser twig, cette objet prend deux paramettres
//le premier on le loaoder, en deuziéme paramettre on a une table d'option, 
$twig = new Twig_Environment($loader, [
    'cache' => false,//__DIR__ . '/tmp',
]);


switch($page){
    case 'home' :
        echo $twig->render('home.twig',['title' => 'Page d\'acueil']);
        break;
    case 'contact' :
        echo $twig->render('contact.twig',['title' => 'Page contact']);
        break;
    case 'posts' :
        echo $twig->render('viewPosts.twig',['title' => 'Articles']);
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

    

        
