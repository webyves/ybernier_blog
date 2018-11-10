<?php
/*****************************************************************
file: Manager.php
Model file
Mother Class for manager class
******************************************************************/
namespace yBernier\Blog\model\manager;

class Manager
{
    
    /***********************************
        Function for DataBase Connexion
    ***********************************/
    protected function dbConnect()
    {
        $dbObject = new \PDO('mysql:host='.$GLOBALS['dbHost'].';dbname='.$GLOBALS['dbName'].';charset=utf8', $GLOBALS['dbUser'], $GLOBALS['dbUserPwd']);
        return $dbObject;
    }
}
