<?php
namespace models;
//require 'Database.php';
/**
 * this class allows to manage the comments sent by the users
 */
class CommentManager extends Database{
    private $pdo;
    public function __construct(){
        $this->pdo = $this->getPDO();
    }
    
    /**
     * getComments
     *this function receives in parameter the identifier of the post and returns their validated comments
     * @param  int $id the identifier of the post
     *
     * @return array validated comments
     */
    public function getComments(int $id):array{
        
        $req=$this->pdo->prepare('SELECT comment_id,content,comments.validated,post_id,date_format(commented,\' %d\%m\%Y à %Hh%imin%ss\') as coment_date,users.username
        FROM comments INNER JOIN users ON comments.user_id = users.user_id WHERE comments.post_id = ? 
        AND comments.validated IS NOT NULL
        ORDER BY coment_date DESC');
        $req->execute([$id]);
        return $comments= $req->fetchAll(\PDO::FETCH_OBJ);
    }  
    /**
     * addComment
     * this function allows to add a comment
     * @param  int $postId
     * @param  int $userId
     * @param  string $content
     *
     * @return boolean true if the insertion affected
     */
    public function addComment(int $postId,int $userId,string $content):bool {
        $comment=$this->pdo->prepare('INSERT INTO comments(commented,content,post_id,user_id) VALUES (now(),:content,:post_id,:user_id)');
		return $comment->execute([
            'post_id' => $postId,
			'user_id' =>$userId,
			'content' =>$content
        ]);
		
    } 

    public function deleteComments(int $idPost):void
    {
        $req=$this->pdo->prepare('DELETE FROM comments WHERE post_id=?');
        $req->execute([$idPost]);
    } 

    public function validateComment($id_comment)
    {
        $req=$this->pdo->prepare('UPDATE comments SET validated=1 WHERE comment_id=?');
        $req->execute([$id_comment]);
    }

    public function invalidComments($id_post)
    {
        $req=$this->pdo->prepare('SELECT comment_id,commented,content,comments.validated,post_id,users.username 
        FROM comments INNER JOIN users ON comments.user_id = users.user_id 
        WHERE comments.validated IS NULL AND comments.post_id=?');
        $req->execute([$id_post]);
        $comments = $req->fetchAll(\PDO::FETCH_OBJ);
        
        return $comments;
    }
}
