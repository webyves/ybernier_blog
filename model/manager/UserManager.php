<?php
/***************************************************************** 
file: UserManager.php 
model for users
******************************************************************/
namespace yBernier\Blog\model\manager;
require_once("model/manager/Manager.php");

Class UserManager extends Manager
{
    /* get user information */
    public function getUser($idUser)
    {
        // return table with user information
    }

    /* get content of post */
    public function userConnect($email, $pwd)
    {
        // return iduser if connection ok
    }

    /* Set users information bloc of public functions */
    public function setUserFname($idUser, $firstName)
    {
        
    }

    public function setUserLname($idUser, $lastName)
    {
        
    }

    public function setUserEmail($idUser, $email)
    {
        
    }

    public function setUserPwd($idUser, $pwd)
    {
        
    }

    public function setUserRole($idUser, $idRole)
    {
        
    }

    public function setUserState($idUser, $idState)
    {
        
    }
}
