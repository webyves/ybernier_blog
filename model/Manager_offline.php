<?php
/***************************************************************** 
file: Manager.php 
Model file
Mother Class for post comment user
******************************************************************/
namespace yBernier\Blog\Model;

Class Manager {
    
    protected function dbConnect()
    {
        $dbUser = "root";
        $dbUserPwd = "";
        $dbHost = "localhost";
        $dbName = "ybernier_blog";

        $db = new \PDO('mysql:host='.$dbHost.';dbname='.$dbName.';charset=utf8', $dbUser, $dbUserPwd);
        return $db;
    }
}