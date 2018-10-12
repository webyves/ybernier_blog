<?php
/***************************************************************** 
file: autoload.php 
autoloader 
******************************************************************/
namespace yBernier\Blog;

Class Autoloader{
    
    static function register(){
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }
    
    static function autoload($class){
        $class = str_replace(__NAMESPACE__ . '\\', '', $class);
        $class = str_replace('\\', '/', $class);
        require $class . '.php';
    }
}