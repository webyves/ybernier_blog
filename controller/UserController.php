<?php
/*****************************************************************
file: UserController.php
website User controller
******************************************************************/
namespace yBernier\Blog\controller;

use \yBernier\Blog\App;
use \yBernier\Blog\model\manager\UserManager;
use \yBernier\Blog\model\manager\PostManager;
use \yBernier\Blog\model\manager\CommentManager;

class UserController extends PageController
{
    /***********************************
        Function to connect User by form
    ***********************************/
    public function connexion()
    {
        $post = $this->fApp->getFPost();
        if (!empty($post['conexEmail']) && !empty($post['conexInputPassword'])) {
            $UserConnected = $this->connect($post['conexEmail'], $post['conexInputPassword']);
            if (isset($post['conexChkbxRemember'])) {
                $this->fApp->generateUserCookie($UserConnected);
            }
        } else {
            throw new \Exception('Les informations envoyées sont incompletes');
        }
        $this->fApp->setConnectedUser($UserConnected);
        $this->fApp->redirect('PostController', 'listPosts');
    }
    
    /***********************************
        Function to disconnect User
    ***********************************/
    public function deconnexion()
    {
        $this->fApp->setConnectedUser(null);
        $this->fApp->destroyUserCookie();
        $this->fApp->destroyUserSession();
        $this->fApp->redirect('PostController', 'listPosts');
    }
    
    /***********************************
        Function to connect User
            Check if it's not blocked one
    ***********************************/
    public function connect($email, $pwd)
    {
        $Manager = new UserManager();
        $user = $Manager->getUser("", $email);
        if (is_null($user)) {
            throw new \Exception('Erreur d\'identification');
        }
        
        if ($user->getIdstate() == 2) {
            throw new \Exception('Vous ne pouvez vous connecter car votre compte n\'est pas validé par un administrateur.');
        }

        if (!password_verify($pwd, $user->getPassword())) {
            throw new \Exception('Identification Incorrecte !');
        }
        return $user;
    }
    
    /***********************************
        Function to connect User via cookie
    ***********************************/
    public function getCookieInfo()
    {
        $Manager = new UserManager();
        $user = $Manager->getUser("", "", $this->fApp->getFCookieUser());
        
        if (is_null($user)) {
            $this->fApp->destroyUserCookie();
            throw new \Exception('Erreur de cookie');
        }
        
        if ($user->getIdstate() == 2) {
            $this->fApp->destroyUserCookie();
            throw new \Exception('Vous ne pouvez vous connecter car votre compte n\'est pas validé par un administrateur.');
        }
        
        return $user;
    }
    
    /***********************************
        Function for user inscription
            check possible error
            send correct infos to user manager
            send email to admin
    ***********************************/
    public function inscription()
    {
        $post = $this->fApp->getFPost();
        $this->checkCaptchaV2($post);

        $errorMessage = "";
        foreach ($post as $postKey => $postValue) {
            if (empty($postValue)) {
                if (!empty($errorMessage)) {
                    $errorMessage .= "<br>";
                }
                switch ($postKey) {
                    case 'inscripFirstname':
                        $errorMessage .= "Veuillez saisir un Prénom";
                        break;
                    case 'inscripLastname':
                        $errorMessage .= "Veuillez saisir un Nom";
                        break;
                    case 'inscripEmail':
                        $errorMessage .= "Veuillez saisir un eMail";
                        break;
                    case 'inscripPassword':
                        $errorMessage .= "Veuillez saisir un Mot de passe";
                        break;
                    case 'inscripPasswordVerif':
                        $errorMessage .= "Veuillez saisir la verification du mot de passe";
                        break;
                    default:
                        $errorMessage .= "Veuillez verifier vos champs";
                        break;
                }
            }
        }
        if ($post['inscripPassword'] != $post['inscripPasswordVerif']) {
            if (!empty($errorMessage)) {
                $errorMessage .= "<br>";
            }
            $errorMessage .= "La verification de password a échoué";
        }
        
        $Manager = new UserManager();
        $checkUser = $Manager->getUser("", strip_tags($post['inscripEmail']));
        if (null !== $checkUser->getEmail()) {
            $errorMessage = "<strong>Il existe déjà un utilisateur avec cet email.</strong><br>Veuillez choisir un autre eMail ou <a href='index.php?p=contact'>contacter un Administrateur</a>";
        }
        
        $theView = "frontoffice/inscriptionError.twig";
        $this->setPostListMenu();
        $theViewParam = array('errorMessage' => $errorMessage, 'postListMenu' => $this->postListMenu);
        if (empty($errorMessage)) {
            $userInfo = array(
                            'firstName' => strip_tags($post['inscripFirstname']),
                            'lastName' => strip_tags($post['inscripLastname']),
                            'eMail' => strip_tags($post['inscripEmail']),
                            'password' => $post['inscripPassword']
                );
            $Manager->addUser($userInfo);
            
            $tabInfo = array(
                    'fromFirstname' =>  "Administrateur",
                    'fromLastname' => "yBernier Blog",
                    'fromEmail' => App::ADMIN_EMAIL,
                    'toEmail' => App::ADMIN_EMAIL,
                    'messageTxt' => "Nouvelle Inscription sur le blog, Merci de valider ".$userInfo['firstName']." ".$userInfo['lastName']." ".$userInfo['eMail'],
                    'messageHtml' => "",
                    'subject' => "[yBernier Blog] - Nouvelle Inscription"
                );
            $this->sendMail($tabInfo);
            $theView = "frontoffice/inscriptionConfirm.twig";
            $this->setPostList();
            $theViewParam = array('postList' => $this->postList, 'postListMenu' => $this->postListMenu);
        }

        echo $this->fTwig->render($theView, $theViewParam);
    }

    /***********************************
        Function for Admin user List
    ***********************************/
    public function showAdminUserList($messageTwigView = "")
    {
        $authRole = array(1);
        $this->checkAccessByRole($this->fApp->getConnectedUser(), $authRole);

        $Manager = new UserManager();
        $userList = $Manager->getUsers();
        $userRoleList = $Manager->getRoleList();
        $userStateList = $Manager->getStateList();
        
        echo $this->fTwig->render('backoffice/adminUsers'.$messageTwigView.'.twig', array('userList' => $userList, 'userRoleList' => $userRoleList, 'userStateList' => $userStateList));
    }
    
    /***********************************
        Function for Admin user modification form
    ***********************************/
    public function modifUser()
    {
        $post = $this->fApp->getFPost();
        $authRole = array(1);
        $this->checkAccessByRole($this->fApp->getConnectedUser(), $authRole);
        
        if (is_numeric($post['userModalIdUser']) && $post['userModalIdUser'] > 0) {
            if (is_numeric($post['userModalSelRole']) && $post['userModalSelRole'] > 0 &&
            is_numeric($post['userModalSelEtat']) && $post['userModalSelEtat'] > 0) {
                $userManager = new UserManager();
                $tab = array (
                    'iduser' => $post['userModalIdUser'],
                    'idstate' => $post['userModalSelEtat'],
                    'idrole' => $post['userModalSelRole']
                    );
                $userManager->updateUser($tab);
                
                if (isset($post['userModalChkbxUpdPostState']) && $post['userModalSelEtat'] == 2) {
                    $postManager = new PostManager();
                    $tab = array (
                        'id_user' => $post['userModalIdUser'],
                        'id_state' => $post['userModalSelEtat']
                        );
                    $postManager->updatePost($tab);
                }
                if (isset($post['userModalChkbxUpdComState']) && $post['userModalSelEtat'] == 2) {
                    $commentManager = new CommentManager();
                    $tab = array (
                        'iduser' => $post['userModalIdUser'],
                        'idstate' => $post['userModalSelEtat']
                        );
                    $commentManager->updateComment($tab);
                }
                
                if (isset($post['userModalChkbxSendMail'])) {
                    $user = $userManager->getUser($post['userModalIdUser']);
                    $emailInfo = array(
                            'fromFirstname' => "Administrateur",
                            'fromLastname' => "yBernier Blog",
                            'fromEmail' => App::ADMIN_EMAIL,
                            'toEmail' =>  $user->getEmail(),
                            'messageTxt' => "Votre compte à bien été mis à jour : ".$user->getRole()." ".$user->getState(),
                            'messageHtml' => "",
                            'subject' => "[yBernier Blog] - Mise à jour de votre compte"
                        );
                    $this->sendMail($emailInfo);
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
