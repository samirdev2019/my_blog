<?php
/**
 * class alow to autoload the class without use lost of require command
 */
class Autoloder{

    /**
     * register
     *@param array first param the class name , the second param the function to execute
     * @return void
     */
    static function register(){
        spl_autoload_register(array(__CLASS__,'autoload'));
    }
    
    /**
     * autoload
     *
     * @param  string $class_name the class a autoload
     *
     * @return void
     */
    static function autoload($class_name){
        require 'class/'.$class_name.'.php';
    }
}
