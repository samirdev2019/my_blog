<?php
namespace App\controllers;

use App\models\PostManager as PostManager;
use App\models\CommentManager as CommentManager;

class PostController
{
    private $postManager;
    public function __construct()
    {
        $this->postManager = new PostManager('myblog');
    }
    
    public function getListPosts():array
    {
        return $this->postManager->getPosts();
    }
 
    public function getPost(int $id):object
    {
        return $this->postManager->getPost($id);
    }
 
    public function addPost(array $post = []):void
    {
        $this->postManager->addPost($post);
    }
    
    public function updatePost(array $post):void
    {
        $this->postManager->updatePost($post);
    }
  
    public function deletePost(int $idPost):void
    {
        $this->postManager->deletePost($idPost);
    }
    
    public function checkInformationPost(array $infoPost):bool
    {
        if (empty($infoPost['title']) || empty($infoPost['chapo'])) {
            return false;
        }
        if (empty($infoPost['author']) || empty($infoPost['title']) || empty($infoPost['content'])) {
            return false;
        } else {
            return true;
        }
    }
    private function getTwig():object
    {
        $loader = new \Twig_Loader_Filesystem(
            [
            '../App/views','../App/views/backend','../App/controllers'
            ]
        );
        return $twig = new \Twig_Environment($loader, ['cache' => false]);
    }
    /**
     * ManagePost function allows to manage the articles according
     * to the action wanted by the user, by sending him messages according
     *  to the case
     *
     * @param string $action         the action wanted by the user
     * @param mixed  $messageSuccess message to send to user
     *
     * @return void
     */
    public function managePost(string $action, string $messageSuccess):void
    {
        $twig=$this->getTwig();
        if (!empty($_POST) && $this->checkInformationPost($_POST)) {
            $this->$action($_POST);
            echo $twig->render(
                $action.'.twig',
                ['title' => 'account',
                'session'=> $_SESSION,
                'message' => $messageSuccess,
                'type' => 'success'
                ]
            );
        } else {
            echo $twig->render(
                $action.'.twig',
                ['title' => 'account',
                'session'=> $_SESSION,
                'message' => 'veuillez remplire tous les champs si-desous',
                'type' => 'danger'
                ]
            );
        }
    }
    /**
     * ActionToDo
     *
     * @param string $action action wanted by the user;
     * @param int    $id     post id;
     *
     * @return void
     */
    public function actionToDo(string $action, $id = null):void
    {
        switch ($action) {
            case 'addPost':
                $this->managePost($action, 'votre article a été bien enregisté');
                break;
            case 'updatePost':
                $this->managePost($action, 'votre article a été bien modifié');
                break;
            case 'deletePost':
                $comments = new CommentManager();
                $twig=$this->getTwig();
                $post=$this->deletePost($id);
                $comments->deleteComments($id);
                $posts=$this->getListPosts();
                echo $twig->render(
                    'ManagerPosts'.'.twig',
                    ['title' => 'account',
                    
                    'message'=>"l'article a été bien suprimé" ,
                    'type'=> 'success',
                    'session'=> $_SESSION,
                    'posts'=>$posts
                    ]
                );

                break;
            case 'ManagerPosts':
                $twig=$this->getTwig();
                $posts=$this->getListPosts();
                echo $twig->render(
                    $action.'.twig',
                    [
                    'title' => 'account',
                    'session'=> $_SESSION,
                    'posts'=>$posts
                    ]
                );
                break;
            case 'posteToUpdate':
                $twig=$this->getTwig();
                $post=$this->getPost($id);
                echo $twig->render(
                    $action.'.twig',
                    [
                    'title' => 'account',
                    'message'=>'vous etes entrain de modifier cet article',
                    'type'=> 'info',
                    'session'=> $_SESSION,
                    'post'=>$post
                    ]
                );
                break;
            default:
                $twig=$this->getTwig();
                header('HTTP/1.0 404 Not Found');
                echo $twig->render('404.twig');
                break;
        }
    }
}
