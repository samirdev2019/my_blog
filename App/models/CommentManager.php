<?php
namespace App\models;

//require 'Database.php';
/**
 * this class allows to manage the comments sent by the users
 */
class CommentManager extends Database
{
    private $pdo;
    public function __construct()
    {
        $this->pdo = $this->getPDO();
    }
    /**
     * getComments
     *this function receives in parameter the identifier of the post and returns their validated comments
     * @param  int $id the identifier of the post
     *
     * @return array validated comments
     */
    public function getComments(int $id):array
    {
        
        $req=$this->pdo->prepare(
            'SELECT comment_id,content,validated,commented_by,post_id,
            date_format(commented,\' %d\%m\%Y à %Hh%imin%ss\') as coment_date
            FROM comments 
            WHERE comments.post_id = ? 
            AND comments.validated IS NOT NULL
            ORDER BY coment_date DESC'
        );
        $req->execute([$id]);
        return $comments= $req->fetchAll(\PDO::FETCH_OBJ);
    }
    /**
     * addComment
     * this function allows to add a comment
     * @param  int $postId
     * @param  string $username
     * @param  string $content
     *
     * @return boolean true if the insertion affected
     */
    public function addComment(int $postId, string $username, string $content):bool
    {
        $comment=$this->pdo->prepare(
            'INSERT INTO comments(commented,content,post_id,commented_by)
            VALUES (now(),:content,:post_id,:commented_by)'
        );
        return $comment->execute(
            [
            'post_id' => $postId,
            'commented_by' => $username,
            'content' =>$content
            ]
        );
    }
    public function deleteComments(int $idPost):void
    {
        $req=$this->pdo->prepare('DELETE FROM comments WHERE post_id=?');
        $req->execute([$idPost]);
    }
    public function validateComment(int $id_comment, int $userId):bool
    {
        $req=$this->pdo->prepare(
            'UPDATE comments SET validated =1,user_id =:userId
            WHERE comment_id = :comment_id'
        );
        return $req->execute(
            [
            'userId' => $userId,
            'comment_id' => $id_comment
            ]
        );
    }
    public function invalidComments(int $id_post):array
    {
        $req=$this->pdo->prepare(
            'SELECT comment_id,commented,commented_by,content,validated,post_id
            FROM comments  
            WHERE comments.validated IS NULL AND comments.post_id=?'
        );
        $req->execute([$id_post]);
        $comments = $req->fetchAll(\PDO::FETCH_OBJ);
        
        return $comments;
    }
}
