<?php
require '../model/PostManager.php';
class PostControler{
    /**
     * getListPosts
     *
     * @return array posts object list
     */
    public function getListPosts(){
        $db = new PostManager('myblog');
        $posts = $db->getPosts();
         
        return $posts;
    }
    /**
     * getPost
     *
     * @param  mixed $id index of post
     *
     * @return object this function return the object that we request using his id
     */
    public function getPost($id){
        $db = new PostManager('myblog');
        $post = $db->getPost($id);
        return $post;
    }
    /**
     * addPost
     *
     * @param  mixed $post
     *
     * @return void
     */
    public function addPost($post){
        $db = new PostManager('myblog');
        $post = $db->addPost($post);
    }
    /**
     * updatePost
     *
     * @param  mixed $post
     *
     * @return void
     */
    public function updatePost($post){
        $db = new PostManager('myblog');
        $post = $db->addPost($post);
    }
    /**
     * deletePost
     *
     * @param  mixed $post
     *
     * @return void
     */
    public function deletePost($post){
        $db = new PostManager('myblog');
        $post = $db->deletePost($post);
    }
}



