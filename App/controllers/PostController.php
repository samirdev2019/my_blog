<?php
namespace App\controllers;

use App\models\PostManager as PostManager;
use App\models\CommentManager as CommentManager;

class PostController
{
    private $postManager;
    /**
     * __construct initialization of PostManager object
     *
     * @return void
     */
    public function __construct()
    {
        $this->postManager = new PostManager('myblog');
    }
    
    /**
     * The getListPosts allow to get a list of all posts
     *
     * @return array
     */
    public function getListPosts():array
    {
        return $this->postManager->getPosts();
    }
 
    /**
     * The getPost allow to get a post by his ID calling the method getPost
     * of the model PostManager
     *
     * @param int $id the post ID
     *
     * @return object
     */
    public function getPost(int $id):object
    {
        return $this->postManager->getPost($id);
    }
    /**
     * The addPost method call the method addPost of the model PostManager
     * for add post in the data base
     *
     * @param array $post post informations
     *
     * @return void
     */
    public function addPost(array $post = []):void
    {
        $this->postManager->addPost($post);
    }
    
    /**
     * The updatePost call the methode of the model postManager for update post
     *
     * @param array $post post informations
     *
     * @return void
     */
    public function updatePost(array $post):void
    {
        $this->postManager->updatePost($post);
    }
  
    /**
     * The deletePost allow to delete an post
     *
     * @param int $idPost post ID
     *
     * @return void
     */
    public function deletePost(int $idPost):void
    {
        $this->postManager->deletePost($idPost);
    }
    
    /**
     * The checkInformationPost verify the expected information from post form
     *
     * @param array $infoPost post informations
     *
     * @return bool
     */
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
    /**
     * The getTwig inicialization of Twig_Loader_Filesystem object
     *
     * @return object
     */
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
        $info = $_POST;
        if (!empty($info) && $this->checkInformationPost($info)) {
            $this->$action($info);
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
     * The actionToDo function reacts according to the action
     * requested by the user of his account, redirecting him to the
     * pages requested and guiding him by messages
     *
     * @param string $action action requested by the user ;
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
