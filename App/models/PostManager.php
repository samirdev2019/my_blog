<?php
namespace App\models;

class PostManager extends Database
{
    private $db;
    /**
     * __construct assign value of PDO object database connection to attribute $db
     *
     * @return void
     */
    public function __construct()
    {
        $this->db =$this->getPDO();
    }
    /**
     * The getPosts request all posts of data base
     *
     * @return array $datas the posts
     */
    public function getPosts():array
    {
        $posts = $this->db->query(
            'SELECT post_id,title,author,chapo,content,user_id,
            date_format(updated,\' %d\%m\%Y à %Hh%imin%ss\') as update_date
            FROM posts ORDER BY updated DESC'
        );
        $datas = $posts->fetchAll(\PDO::FETCH_OBJ);
        return $datas  ;
    }
    /**
     * The getPost get the post from data base
     *
     * @param int $id post ID
     *
     * @return object
     */
    public function getPost(int $id):object
    {
        $post=$this->db->prepare(
            'SELECT post_id,title,author,chapo,content,user_id,
            date_format(updated,\' %d\%m\%Y à %Hh%imin%ss\') as update_date
            FROM posts WHERE post_id = ?'
        );
        $post->execute(array($id));
        return $post->fetch(\PDO::FETCH_OBJ);
    }
    /**
     * The addPost function allow to add post in the data base
     *
     * @param array $post post informations
     *
     * @return void
     */
    public function addPost(array $post):void
    {
        $req=$this->db->prepare(
            'INSERT INTO posts (title,author,chapo,created,updated,content,user_id)
            VALUES (:title,:author,:chapo,now(),now(),:content,:user_id)'
        );
        $req->execute(
            [
            'title' => $post['title'],
            'author' => $post['author'],
            'chapo' => $post['chapo'],
            'content' => $post['content'],
            'user_id' => $post['id_user']
            ]
        );
    }
    /**
     * The updatePost allow to update the post
     *
     * @param array $post post informations
     *
     * @return bool
     */
    public function updatePost(array $post):bool
    {
        $req=$this->db->prepare(
            'UPDATE posts
            SET title=:title,chapo=:chapo,author=:author,content=:content,
            updated=now()
            WHERE post_id=:post_id'
        );
        return $req->execute(
            [
            'post_id' => $post['post_id'],
            'title' => $post['title'],
            'chapo'=> $post['chapo'],
            'author' => $post['author'],
            'content'=> $post['content']
            ]
        );
    }
    /**
     * The deletePost allow to delete a post
     *
     * @param int $idPost the post id
     *
     * @return bool
     */
    public function deletePost(int $idPost):bool
    {
        $req=$this->db->prepare('DELETE FROM posts WHERE post_id=:post_id');
        return $req->execute(['post_id' => $idPost]);
    }
}
