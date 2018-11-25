<?php
/*****************************************************************
file: App.php
Class for Application informations
and methods for passing form data and encapsulation
******************************************************************/
namespace yBernier\Blog;

use \yBernier\Blog\Router;
use \yBernier\Blog\controller\UserController;
use \yBernier\Blog\model\entities\User;

class App extends AppConfig
{
    const APP_VERSION = "V0.202";   // Application version
    
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
    private $errorMessage;
    
    public function __construct()
    {
        $this->intializeEncapsSuperglobals();
    }
    
    /* INITIALISATION */
    private function intializeEncapsSuperglobals()
    {
        $sessionData = $cookiesData = $getData = $postData = $filesData = $serverData = array();
        $getDataI = 0;
        $getDataP = $cookiesDataUser = $serverDataRemAddr = "";
        $UserConnected = null;

        // POST
        if (isset($_POST)) {
            $postData = $_POST;
        }
        $this->fPost = $postData;
        
        // GET
        if (isset($_GET)) {
            $getData = $_GET;
            if (isset($getData['p'])) {
                $getDataP = $getData['p'];
            }
            if (isset($getData['i'])) {
                $getDataI = (int)$getData['i'];
            }
        }
        $this->fGet = $getData;
        $this->fGetP = $getDataP;
        $this->fGetI = $getDataI;
        
        // FILES
        if (isset($_FILES)) {
            $filesData = $_FILES;
        }
        $this->fFiles = $filesData;
        
        // SERVER
        if (isset($_SERVER)) {
            $serverData = $_SERVER;
            if (isset($serverData['REMOTE_ADDR'])) {
                $serverDataRemAddr = $serverData['REMOTE_ADDR'];
            }
        }
        $this->fServer = $serverData;
        $this->fServerRemAddr = $serverDataRemAddr;

        // SESSION
        if (isset($_SESSION)) {
            $sessionData = $_SESSION;
            if (isset($sessionData['userObject'])) {
                $UserConnected = $sessionData['userObject'];
            }
        }
        $this->fSession = $sessionData;
        
        // COOKIE
        if (isset($_COOKIE)) {
            $cookiesData = $_COOKIE;
            if (isset($cookiesData['userIdCookie'])) {
                $cookiesDataUser = $cookiesData['userIdCookie'];
            }
        }
        $this->fCookie = $cookiesData;
        $this->setFCookieUser($cookiesDataUser);
        
        // USER (check cookie & put in session)
        if (is_null($UserConnected) && !empty($cookiesDataUser)) {
            $UserController = new UserController($this);
            $UserConnected = $UserController->getCookieInfo();
        }
        $this->setConnectedUser($UserConnected);
    }
    
    /* SET PARTS */
    public function setConnectedUser($value)
    {
        $this->connectedUser = $value;
        if (!is_null($value)) {
            $this->putUserSession($value);
        }
    }
        
    public function setFCookieUser($value)
    {
        $this->fCookieUser = $value;
    }
        
    public function setErrorMessage($value)
    {
        $this->errorMessage = $value;
    }
        
    public function setFGetP($value)
    {
        $this->fGetP = $value;
    }
        
    public function setFGetI($value)
    {
        $this->fGetI = $value;
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
    
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }
    
    /***********************************
        Function to redirect on a page.
    ***********************************/
    public function redirect($controllerName, $methodName)
    {
        $router = new Router();
        $router->goRoad($this, $controllerName, $methodName);
    }
    
    /***********************************
        Function to put User object in Session
    ***********************************/
    private function putUserSession(User $user)
    {
        $_SESSION['userObject'] = $user;
    }
    
    /***********************************
        Function to Destroy Session
    ***********************************/
    public function destroyUserSession()
    {
        session_destroy();
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
