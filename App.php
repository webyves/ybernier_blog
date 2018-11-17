<?php
/*****************************************************************
file: App.php
Class for Application informations
and methods for passing form data and encapsulation
******************************************************************/
namespace yBernier\Blog;

use \yBernier\Blog\controller\UserController;
use \yBernier\Blog\model\entities\User;

class App extends AppConfig
{
    private $fGet;                  // $_GET
    private $fGetP;                 // $_POST
    private $fGetI;                 // $_GET['i']
    private $fPost;                 // $_GET['p']
    private $fFiles;                // $_FILES
    private $fSession;              // $_SESSION
    private $fCookie;               // $_COOKIE
    private $fCookieUser;           // $_COOKIE['userIdCookie']
    private $fServer;               // $_SERVER
    private $fServerRemAddr;        // $_SERVER['REMOTE_ADDR']
    private $connectedUser;
    
    public function __construct()
    {
        $this->intializeEncapsSuperglobals();
        $this->loginUser();
    }
    
    /* SET PARTS */
    private function setConnectedUser(User $value)
    {
        $this->connectedUser = $value;
        $this->putUserSession($value);
    }
        
    private function setFCookieUser($value)
    {
        $this->fCookieUser = $value;
    }
    
    private function intializeEncapsSuperglobals()
    {
        $sessionData = $cookiesData = $getData = $postData = $filesData = $serverData = array();
        $getDataI = 0;
        $getDataP = $cookiesDataUser = $serverDataRemAddr = "";
        $UserConnected = new User('');

        if (isset($_POST)) {
            $postData = $_POST;
        }
        if (isset($_GET)) {
            $getData = $_GET;
            if (isset($getData['p'])) {
                $getDataP = $getData['p'];
            }
            if (isset($getData['i'])) {
                $getDataI = (int)$getData['i'];
            }
        }
        if (isset($_FILES)) {
            $filesData = $_FILES;
        }
        if (isset($_SESSION)) {
            $sessionData = $_SESSION;
            if (isset($sessionData['userObject'])) {
                $UserConnected = $sessionData['userObject'];
            }
        }
        if (isset($_COOKIE)) {
            $cookiesData = $_COOKIE;
            if (isset($cookiesData['userIdCookie'])) {
                $cookiesDataUser = $cookiesData['userIdCookie'];
            }
        }
        if (isset($_SERVER)) {
            $serverData = $_SERVER;
            if (isset($serverData['REMOTE_ADDR'])) {
                $serverDataRemAddr = $serverData['REMOTE_ADDR'];
            }
        }

        $this->fGet = $getData;
        $this->fGetP = $getDataP;
        $this->fGetI = $getDataI;
        $this->fPost = $postData;
        $this->fFiles = $filesData;
        $this->fSession = $sessionData;
        $this->fCookie = $cookiesData;
        $this->fServer = $serverData;
        $this->fServerRemAddr = $serverDataRemAddr;
        
        $this->setFCookieUser($cookiesDataUser);
        $this->setConnectedUser($UserConnected);
    }
        
    /* GET PARTS */
    public function getFGet()
    {
        return $this->fGet;
    }
    
    public function getFGetI()
    {
        return $this->fGetI;
    }
    
    public function getFGetP()
    {
        return $this->fGetP;
    }
    
    public function getFPost()
    {
        return $this->fPost;
    }
    
    public function getFFiles()
    {
        return $this->fFiles;
    }
    
    public function getFSession()
    {
        return $this->fSession;
    }
    
    public function getFCookie()
    {
        return $this->fCookie;
    }
    
    public function getFCookieUser()
    {
        return $this->fCookieUser;
    }
    
    public function getFServer()
    {
        return $this->fServer;
    }
    
    public function getFServerRemAddr()
    {
        return $this->fServerRemAddr;
    }
    
    public function getConnectedUser()
    {
        return $this->connectedUser;
    }
    
    /***********************************
        Function to Try to connect user if is not in session
    ***********************************/
    public function loginUser() {
        // CONNEXION
        if (is_null($this->getConnectedUser()->getEmail())) {
            $postData = $this->getFPost();
            $UserController = new UserController($this);
            if (isset($postData['conexEmail']) && isset($postData['conexInputPassword'])) {
                $UserConnected = $UserController->connect($postData['conexEmail'], $postData['conexInputPassword']);
                if (isset($postData['conexChkbxRemember'])) {
                    $this->generateUserCookie($UserConnected);
                }
            } else {
                $UserConnected = $UserController->getCookieInfo();
            }
            $this->setConnectedUser($UserConnected);
        }
    }
    
    /***********************************
        Function to logout user 
        and erase it from App Session & Cookie
    ***********************************/
    public function logoutUser()
    {
        $User = new User('');
        $this->setConnectedUser($User);
        $this->destroyUserCookie();
    }
    
    /***********************************
        Function to put User object in Session
    ***********************************/
    private function putUserSession(User $user)
    {
        $_SESSION['userObject'] = $user;
    }
    
    /***********************************
        Generate a cookie with crypted info for connexion
    ***********************************/
    public function generateUserCookie(User $user)
    {
        setcookie("userIdCookie", $user->getCookieid(), time()+129600); //expire 36h
        $this->setFCookieUser($user->getCookieid());
    }

    /***********************************
        Destroy the cookie with connexion info
    ***********************************/
    public function destroyUserCookie()
    {
        setcookie("userIdCookie", "", time()-1);
        $this->setFCookieUser('');
    }
    
}
