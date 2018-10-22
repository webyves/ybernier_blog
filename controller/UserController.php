<?php
/***************************************************************** 
file: UserController.php 
website User controller
******************************************************************/
namespace yBernier\Blog\controller;

use \yBernier\Blog\controller\PageController;
use \yBernier\Blog\model\manager\UserManager;

Class UserController
{
    public function connect($email,$pwd)
    {
        $Manager = new UserManager();
        $user = $Manager->getUser("",$email);
        
        if (!empty($user->getPassword())) {
            if ($pwd == $user->getPassword()) {
                $this->putUserSession($user);
                return $user;
            } else {
                throw new \Exception('Identification Incorrecte !');
            }
        } else {
            throw new \Exception('Utilisateur Incorrect !');
        }
    }
    
    public function putUserSession($userObject)
    {
        $_SESSION['userObject'] = $userObject;
    }

    public function generateUserCookie($userObject)
    {
        setcookie("userIdCookie",$userObject->getIduser());
    }

    public function destroyUserCookie()
    {
        unset($_COOKIE["userIdCookie"]);
    }

    public function getCookieInfo()
    {
        if (isset($_COOKIE["userIdCookie"])) {
            $Manager = new UserManager();
            $user = $Manager->getUser($_COOKIE["userIdCookie"]);
            if (isset($user->getEmail)) {
                $this->putUserSession($user);
                return $user;
            } else {
                $this->destroyUserCookie();
                return null;
            }
        } else {
            return null;
        }
    }
    
    public function inscription($post) 
    {
        // NOT TESTED
        // VERIF DES POST
        // $userInfo = $post;
        // CREATION DE L'UTILISATEUR EN BASE
        // $Manager = new UserManager();
        // $Manager->addUser($userInfo);
        
    }
    
    

}