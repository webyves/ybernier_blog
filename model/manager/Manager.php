<?php
/***************************************************************** 
file: Manager.php 
Model file
Mother Class for manager class
******************************************************************/
namespace yBernier\Blog\model\manager;

Class Manager 
{
    
    /*********************************** 
        Function for DataBase Connexion  
    ***********************************/
    protected function dbConnect()
    {
        $db = new \PDO('mysql:host='.$GLOBALS['dbHost'].';dbname='.$GLOBALS['dbName'].';charset=utf8', $GLOBALS['dbUser'], $GLOBALS['dbUserPwd']);
        return $db;
    }
    

}