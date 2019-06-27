<?php
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
        $comments=$this->pdo->prepare('SELECT comment_id,content,comments.validated,post_id,date_format(commented,\' %d\%m\%Y à %Hh%imin%ss\') as coment_date,users.username
        FROM comments INNER JOIN users ON comments.user_id = users.user_id WHERE post_id = ? 
         ORDER BY coment_date DESC');
        $comments->execute(array($id));
        return $comments->fetchAll(PDO::FETCH_OBJ);
        
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
}
