<?php
require '../model/PostManager.php';
class PostControler{
    private $postManager;
    public function __construct(){
        $this->postManager = new PostManager('myblog');
    }
    /**
     * getListPosts
     *
     * @return array posts object list
     */
    public function getListPosts() {         
        return $this->postManager->getPosts();
    }
    /**
     * getPost
     *
     * @param  mixed $id index of post
     *
     * @return object this function return the object that we request using his id
     */
    public function getPost($id){
        return $this->postManager->getPost($id);
    }
    /**
     * addPost
     *
     * @param  mixed $post
     *
     * @return void
     */
    public function addPost(array $post=[]){
        
        $this->postManager->addPost($post);
    }
    /**
     * updatePost
     *
     * @param  mixed $post
     *
     * @return void
     */
    public function updatePost($post){
        $this->postManager->updatePost($post);
    }
    /**
     * deletePost
     *
     * @param  int $idpost
     *
     * @return void
     */
    public function deletePost(int $idPost):void{
        
        $this->postManager->deletePost($idPost);
    }
    /**
     * checkInformationPost for check the infos come from the user
     *
     * @param  array $infoPost
     *
     * @return bool
     */
    public function checkInformationPost(array $infoPost):bool{
        
        if(empty($infoPost['title']) || empty($infoPost['chapo'])|| empty($infoPost['author']) || empty($infoPost['title']) || empty($infoPost['content']) ){
            return false;
        }else{
           return true;
        }

    }
    /**
     * getTwig
     *
     * @return object $twig Twig_Environment
     */
    private function getTwig(){
        $loader = new Twig_Loader_Filesystem(['../views','../views/backend','../controllers']); 
        return $twig = new Twig_Environment($loader, ['cache' => false]);
       
    }
    /**
     * managePost function allows to manage the articles according 
     * to the action wanted by the user, by sending him messages according
     *  to the case
     *
     * @param  string $action the action wanted by the user
     * @param  mixed $messageSuccess 
     *
     * @return void
     */
    public function managePost(string $action,string $messageSuccess):void{
        $twig=$this->getTwig();
        
        if(!empty($_POST) && $this->checkInformationPost($_POST)){
            $this->$action($_POST);
            echo $twig->render($action.'.twig',[
            'title' => 'account',
            'session'=> $_SESSION,
            'message' => $messageSuccess,
            'type' => 'success'
            ]);
        }else{ 
            
            echo $twig->render($action.'.twig',[
                'title' => 'account',
                'session'=> $_SESSION,
                'message' => 'veuillez remplire tous les champs si-desous',
                'type' => 'danger'
                
            ]);
        }
       

    }
    /**
     * actionToDo
     *
     * @param string  $action action wanted by the user
     * @param  int $id post id
     *
     * @return void
     */
    public function actionToDo(string $action,$id=null):void{
        
        switch($action){
            case 'addPost':
            $this->managePost($action,'votre article a été bien enregisté');
            break;
            case 'updatePost':
            $this->managePost($action,'votre article a été bien modifié');
            break;
            case 'deletePost':
            $twig=$this->getTwig();
            $post=$this->deletePost($id);
            $posts=$this->getListPosts();
            echo $twig->render('ManagerPosts'.'.twig',[
                'title' => 'account',
                'message'=>"l'article a été bien suprimé" ,
                'type'=> 'success',
                'session'=> $_SESSION,
                'posts'=>$posts
                
            ]);

            break;
            case 'ManagerPosts':
            $twig=$this->getTwig();
            $posts=$this->getListPosts();
            echo $twig->render($action.'.twig',[
                'title' => 'account',
                'session'=> $_SESSION,
                'posts'=>$posts
                
            ]);
            break;
            case 'posteToUpdate':
            $twig=$this->getTwig();
            $post=$this->getPost($id);
            echo $twig->render($action.'.twig',[
                'title' => 'account',
                'message'=>'vous etes entrain de modifier cet article',
                'type'=> 'info',
                'session'=> $_SESSION,
                'post'=>$post
                
            ]);

            break;
            default :
            header('HTTP/1.0 404 Not Found');
            echo $twig->render('404.twig');
            break;

        }
    }
}
