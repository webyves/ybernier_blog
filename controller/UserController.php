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
            Check if it's not blocked one
    ***********************************/
    public function connect($email,$pwd)
    {
        $Manager = new UserManager();
        $user = $Manager->getUser("",$email);
        
        if ($user->getIdstate() == 2)
            throw new \Exception('Vous ne pouvez vous connecter car vous n\'avez pas encore été validé par un administrateur.');
        
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
            send email to admin
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
            
            $tabInfo = array( 
                    'fromFirstname' =>  "Administrateur",
                    'fromLastname' => "yBernier Blog",
                    'fromEmail' => $GLOBALS['adminEmail'],
                    'toEmail' => $GLOBALS['adminEmail'],
                    'messageTxt' => "Nouvelle Inscription sur le blog, Merci de valider ".$post['inscripFirstname']." ".$post['inscripLastname']." ".$post['inscripEmail'],
                    'messageHtml' => "",
                    'subject' => "[yBernier Blog] - Nouvelle Inscription"                         
                );
            $Manager-> sendMail($tabInfo);
            
            echo $this->fTwig->render('frontoffice/inscriptionConfirm.twig', array('postList' => $this->postList, 'postListMenu' => $this->postListMenu));
        } else {
            echo $this->fTwig->render('frontoffice/inscriptionError.twig', array('errorMessage' => $errorMessage, 'postListMenu' => $this->postListMenu));
        }
    }

    /*********************************** 
        Function for Admin user List
    ***********************************/
    public function showAdminUserList($messageTwigView = "") 
    {
        $authRole = array(1);
        $this->checkAccessByRole($_SESSION['userObject'], $authRole);

        $Manager = new UserManager();
        $userList = $Manager->getUsers();
        $userRoleList = $Manager->getRoleList();
        $userStateList = $Manager->getStateList();
        
        echo $this->fTwig->render('backoffice/adminUsers'.$messageTwigView.'.twig', array('userList' => $userList, 'userRoleList' => $userRoleList, 'userStateList' => $userStateList));
    }
    
    /*********************************** 
        Function for Admin user modification form
    ***********************************/
    public function modifUser($post) 
    {
        $authRole = array(1);
        $this->checkAccessByRole($_SESSION['userObject'], $authRole);
        
        if (is_numeric($post['userModalIdUser']) && $post['userModalIdUser'] > 0) {
            if (is_numeric($post['userModalSelRole']) && $post['userModalSelRole'] > 0 &&
            is_numeric($post['userModalSelEtat']) && $post['userModalSelEtat'] > 0) {
                $Manager = new UserManager();

                $tab = array (
                    'iduser' => $post['userModalIdUser'],
                    'idstate' => $post['userModalSelEtat'],
                    'idrole' => $post['userModalSelRole']
                    );
                $Manager->updateUser($tab);
                
                if (isset($post['userModalChkbxSendMail'])) {
                    $user = $Manager->getUser($post['userModalIdUser']);
                    $emailInfo = array( 
                            'fromFirstname' => "Administrateur",
                            'fromLastname' => "yBernier Blog",
                            'fromEmail' => $GLOBALS['adminEmail'],
                            'toEmail' =>  $user->getEmail(), 
                            'messageTxt' => "Votre compte à bien été mis à jour : ".$user->getRole()." ".$user->getState(),
                            'messageHtml' => "",
                            'subject' => "[yBernier Blog] - Mise à jour de votre compte"                         
                        );
                    $Manager-> sendMail($emailInfo);
                }
                
                $this->showAdminUserList('Confirm');
                
            } else {
                throw new \Exception('Valeure Incorrecte !');
            }
        } else {
            throw new \Exception('Utilisateur Incorrect !');
        }
    }
    
}