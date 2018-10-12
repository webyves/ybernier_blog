<?php
/***************************************************************** 
file: Manager.php 
Model file
Mother Class for manager class
******************************************************************/
namespace yBernier\Blog\model\manager;

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