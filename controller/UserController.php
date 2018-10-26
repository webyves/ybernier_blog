<?php
/***************************************************************** 
file: UserController.php 
website User controller
******************************************************************/
namespace yBernier\Blog\controller;

use \yBernier\Blog\model\manager\UserManager;
use \yBernier\Blog\model\manager\PostManager;


Class UserController
{
    
    protected $postList;
    protected $postListMenu;
    protected $fTwig;
    
    public function __construct()
    {
        $this->setFTwig();
        $this->setPostList();
        $this->setPostListMenu();
    }
    
    /* SET FUNCTION PARTS */
    public function setPostListMenu()
    {
        $postManager = new PostManager();
        $this->postListMenu = $postManager->getPosts();
    }
    
    public function setPostList()
    {
        $postManager = new PostManager();
        $this->postList = $postManager->getPosts('full_list');
    }
    
    public function setFTwig()
    {
        global $twig;
        $this->fTwig = $twig;
    }
    
    
    
    public function connect($email,$pwd)
    {
        $Manager = new UserManager();
        $user = $Manager->getUser("",$email);
        
        if (!empty($user->getPassword())) {
            if (password_verify($pwd, $user->getPassword())) {
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
        setcookie("userIdCookie", $userObject->getCookieid(), time()+129600); //expire 36h
    }

    public function destroyUserCookie()
    {
        unset($_COOKIE["userIdCookie"]);
    }

    public function getCookieInfo()
    {
        if (isset($_COOKIE["userIdCookie"])) {
            $Manager = new UserManager();
            $user = $Manager->getUser("","",$_COOKIE["userIdCookie"]);
            if (null !== $user->getEmail()) {
                $this->putUserSession($user);
                $this->generateUserCookie($user);
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
        
        // VERIF DES POST
        $errorMessage = "";
        foreach ($_POST as $postKey => $postValue) {
            if (empty($postValue)) {
                if (!empty($errorMessage))
                   $errorMessage .= "<br>";
                switch ($postKey) {
                    case 'inscripFirstname' :
                        $errorMessage .= "Veuillez saisir un Prénom"; 
                        break;
                    case 'inscripLastname' :
                        $errorMessage .= "Veuillez saisir un Nom"; 
                        break;
                    case 'inscripEmail' :
                        $errorMessage .= "Veuillez saisir un eMail"; 
                        break;
                    case 'inscripPassword' :
                        $errorMessage .= "Veuillez saisir un Mot de passe"; 
                        break;
                    case 'inscripPasswordVerif' :
                        $errorMessage .= "Veuillez saisir la verification du mot de passe"; 
                        break;
                    default :     
                        $errorMessage .= "Veuillez verifier vos champs."; 
                        break;
                    
                }
            }
        }
        if ($post['inscripPassword'] != $post['inscripPasswordVerif']) {
            if (!empty($errorMessage))
               $errorMessage .= "<br>";
            $errorMessage .= "La verification de password a échoué";
        }
        
        if (empty($errorMessage)) {
            $userInfo = array(
                            'firstName' => $post['inscripFirstname'],
                            'lastName' => $post['inscripLastname'],
                            'eMail' => $post['inscripEmail'],
                            'password' => $post['inscripPassword']
                );
            $Manager = new UserManager();
            $Manager->addUser($userInfo);
            echo $this->fTwig->render('inscriptionConfirm.twig', array('postList' => $this->postList, 'postListMenu' => $this->postListMenu));
        } else {
            echo $this->fTwig->render('inscriptionError.twig', array('errorMessage' => $errorMessage, 'postListMenu' => $this->postListMenu));
        }
    }

}