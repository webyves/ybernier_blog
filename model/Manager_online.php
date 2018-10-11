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
        $dbUser = "ybernierog83";
        $dbUserPwd = "kvZ13dlC";
        $dbHost = "ybernierog83.mysql.db";
        $dbName = "ybernierog83";

        $db = new \PDO('mysql:host='.$dbHost.';dbname='.$dbName.';charset=utf8', $dbUser, $dbUserPwd);
        return $db;
    }
}