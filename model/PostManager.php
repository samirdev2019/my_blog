<?php

require 'Database.php';
class PostManager extends Database{
    private $db;
    
    public function getPosts(){
        $db =$this->getPDO();
        
        $posts = $db->query('SELECT post_id,title,author,chapo,content,date_format(updated,\' %d\%m\%Y à %Hh%imin%ss\') as update_date FROM posts ORDER BY update_date DESC');
        $datas = $posts->fetchAll(PDO::FETCH_OBJ);
        
		return $datas  ;

    }
}
