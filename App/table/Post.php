<?php
namespace APP\table\Post;
class Post{
    public function getURL(){
        return 'index.php?p=post&id='.$this->post_id;
    }
    public function getContent(){
        $html ='<p>'.substr($this->content,0 ,50). '...</p>';
        $html.='<p><a href="' .$this->getURL().'">Voir plus de détail</a></p>';
        return $html;
    }
}
