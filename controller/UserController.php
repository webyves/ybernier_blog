<?php
/***************************************************************** 
file: UserController.php 
website User controller
******************************************************************/
namespace yBernier\Blog\controller;

use \yBernier\Blog\model\manager\UserManager;

Class UserController extends PageController
{
    /*********************************** 
        Function to connect User 
    ***********************************/
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
    
    /*********************************** 
        SubFunction to connect User 
            Put User object in Session
    ***********************************/
    public function putUserSession($userObject)
    {
        $_SESSION['userObject'] = $userObject;
    }

    /*********************************** 
        SubFunction to connect User 
            Generate a cookie with crypted info for connexion
    ***********************************/
    public function generateUserCookie($userObject)
    {
        setcookie("userIdCookie", $userObject->getCookieid(), time()+129600); //expire 36h
    }

    /*********************************** 
        SubFunction for cookie User 
            Destroy the cookie
    ***********************************/
    public function destroyUserCookie()
    {
        setcookie("userIdCookie", "", time()-1);
    }

    /*********************************** 
        Function to connect User via cookie
    ***********************************/
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
    
    /*********************************** 
        Function for user inscription 
            check possible error
            send correct infos to user manager
    ***********************************/
    public function inscription($post) 
    {
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