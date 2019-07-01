<?php
namespace controllers;

use models\CommentManager as CommentManager;
//require '../model/CommentManager.php';
class CommentController{
    private $db;

    /**
     * __construct
     * pdo initialisation 
     * @return void
     */
    public function __construct(){
        $this->db = new CommentManager('mybolg');
    }
    /**
     * getComments
     *this function receives in parameter the post identifier and request the model
     * for their validated comments
     * @param  int $id the post identifier
     *
     * @return array objects array of validated comments
     */
    public function getComments(int $id): array{
        $id = (int) $id;
        if($id > 0){
            return $this->db->getComments($id);
        }else{
            require '../public/index.php?p=posts';
        }
    }
    /**
     * addComment
     * this function allows to add a comment
     * @param  mixed $postId the post identifier
     * @param  mixed $userId the user identifier
     * @param  mixed $content the user comment to send
     *
     * @return void
     */
    public function addComment(int $postId,int $userId,string $content): void{
        $this->db->addComment($postId,$userId,$content);
    }
    public function getInvalidComments($id_post)
    {
        return $this->db->invalidComments($id_post);
    }
    public function validateComment($id_comment)
    {
        return $this->db->validateComment($id_comment);
    }
}
