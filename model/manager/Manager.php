<?php
/*****************************************************************
file: Manager.php
Model file
Mother Class for manager class
******************************************************************/
namespace yBernier\Blog\model\manager;

use \yBernier\Blog\App;

class Manager
{
    
    /***********************************
        Function for DataBase Connexion
    ***********************************/
    protected function dbConnect()
    {
        $dbObject = new \PDO('mysql:host='.App::DB_HOST.';dbname='.App::DB_NAME.';charset=utf8', App::DB_USER, App::DB_USER_PWD);
        return $dbObject;
    }
}
