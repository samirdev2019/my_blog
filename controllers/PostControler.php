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
     * @param  mixed $post
     *
     * @return void
     */
    public function deletePost($idPost){
        
        $this->postManager->deletePost($idPost);
    }
    public function checkInformationPost(array $infoPost){
        
        if(empty($infoPost['title']) || empty($infoPost['chapo'])|| empty($infoPost['author']) || empty($infoPost['title']) || empty($infoPost['content']) ){
            return false;
        }else{
           return true;
        }

    }
    private function getTwig(){
        //session_start();
        $loader = new Twig_Loader_Filesystem(['../views','../views/backend','../controllers']); 
        return $twig = new Twig_Environment($loader, ['cache' => false]);
       
    }
    public function managePost($action,$messageSuccess){
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
    public function actionToDo($action,$id=null){
        
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
            // var_dump($post);die('post controleur');
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
            // var_dump($post);die('post controleur');
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
}//'class/'.$class_name.'.php';
// if($action==='addPost'){
            
//     $this->managePost($action,'votre article a été bien enregisté');
// }elseif($action==='updatePost'){

//     $this->managePost($action,'votre article a été bien enregisté');
// }elseif({
//     $this->managePost($action,'votre article a été bien suprimé');
// }