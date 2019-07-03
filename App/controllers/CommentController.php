<?php
namespace controllers;

use models\CommentManager as CommentManager;

class CommentController
{
    private $commentManager;
    /**
     *__construct
     *pdo initialisation
     *@return void
     */
    public function __construct()
    {
        $this->commentManager = new CommentManager('mybolg');
    }
    /**
     * getComments
     *this function receives in parameter the post identifier and request the model
     * for their validated comments
     * @param  int $id the post identifier
     *
     * @return array objects array of validated comments
     */
    public function getComments(int $id):array
    {
        $id = (int) $id;
        if ($id > 0) {
            return $this->commentManager->getComments($id);
        } else {
            require '../public/index.php?p=posts';
        }
    }
    /**
     * addComment
     * this function allows to add a comment
     * @param  int $postId the post identifier
     * @param  string $username the user identifier
     * @param  string $content the user comment to send
     *
     * @return void
     */
    public function addComment(int $postId, string $username, string $content):void
    {
        $this->commentManager->addComment($postId, $username, $content);
    }
    public function getInvalidComments(int $id_post):array
    {
        return $this->commentManager->invalidComments($id_post);
    }
    public function validateComment(int $id_comment, int $userId):bool
    {
        return $this->commentManager->validateComment($id_comment, $userId);
    }
}
