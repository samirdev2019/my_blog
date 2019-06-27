<?php

require 'Database.php';
class PostManager extends Database{
    private $db;
    public function __construct(){
        $this->db =$this->getPDO();
    }
    public function getPosts(){
        $posts = $this->db->query('SELECT post_id,title,author,chapo,content,date_format(updated,\' %d\%m\%Y à %Hh%imin%ss\') as update_date,user_id FROM posts ORDER BY update_date DESC');
        $datas = $posts->fetchAll(PDO::FETCH_OBJ);
        return $datas  ;
    }
    public function getPost($id){
        $post=$this->db->prepare('SELECT post_id,title,author,chapo,content,date_format(updated,\' %d\%m\%Y à %Hh%imin%ss\') as update_date,user_id FROM posts WHERE post_id = ? ORDER BY update_date DESC');
		$post->execute(array($id));
		return $post->fetch(PDO::FETCH_OBJ);
    }
    public function addPost(array $post):void {
        $req=$this->db->prepare('INSERT INTO posts (title,author,chapo,created,updated,content,user_id)
        VALUES (:title,:author,:chapo,now(),now(),:content,:user_id)');
        $req->execute([
            'title' => $post['title'],
            'author' => $post['author'],
            'chapo' => $post['chapo'],
            'content' => $post['content'],
            'user_id' => $post['id_user']
        ]);
    }
    public function updatePost(array $post){
        $req=$this->db->prepare('UPDATE posts SET title=:title,chapo=:chapo,author=:author,content=:content,updated=now()  WHERE post_id=:post_id');
        return $req->execute([
            'post_id' => $post['post_id'],
            'title' => $post['title'],
            'chapo'=> $post['chapo'],
            'author' => $post['author'],
            'content'=> $post['content']
        ]);
    }
    public function deletePost($idPost){
        $req=$this->db->prepare('DELETE FROM posts WHERE post_id=:post_id');
        return $req->execute([
            'post_id' => $idPost
        ]);
    }
}
