<?php
/***************************************************************** 
file: db.php 
model for database connection
******************************************************************/

function dbConnect()
{
    $dbUser = "root";
    $dbUserPwd = "";
    $dbHost = "localhost";
    $dbName = "ybernier_blog";

    $db = new PDO('mysql:host='.$dbHost.';dbname='.$dbName.';charset=utf8', $dbUser, $dbUserPwd);
    return $db;
}