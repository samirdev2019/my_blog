<?php
class Post{

    public function getURL(){
        return 'index.php?post&id='.$this->id;
    }
    public function getExtrait(){
        $html = '<p>' . $this->content .'</p>';
        $html .= '<p><a href="' .$this->getURL().'"> plus de détail</a></p>';
    }
}