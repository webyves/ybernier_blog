<?php
/*****************************************************************
file: App.php
Class for Application informations
and methods for passing form data and encapsulation
******************************************************************/
namespace yBernier\Blog;

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
    private $connectedUser;
    
    public function __construct()
    {
        $this->setEncapsSuperglobals();
        $this->connectedUser = new User('');
    }
    
    /* SET PARTS */
    public function setConnectedUser(User $value)
    {
        $this->connectedUser = $value;
    }
        
    private function setEncapsSuperglobals()
    {
        $sessionData = $cookiesData = $getData = $postData = $filesData = array();
        $getDataI = 0;
        $getDataP = $cookiesDataUser = "";
        if (isset($_POST)) {
            $postData = $_POST;
        }
        if (isset($_GET)) {
            $getData = $_GET;
            if (isset($_GET['p'])) {
                $getDataP = $_GET['p'];
            }
            if (isset($_GET['i'])) {
                $getDataI = $_GET['i'];
            }
        }
        if (isset($_FILES)) {
            $filesData = $_FILES;
        }
        if (isset($_SESSION)) {
            $sessionData = $_SESSION;
        }
        if (isset($_COOKIE)) {
            $cookiesData = $_COOKIE;
            if (isset($_COOKIE['userIdCookie'])) {
                $cookiesDataUser = $_COOKIE['userIdCookie'];
            }
        }

        $this->fGet = $getData;
        $this->fGetP = $getDataP;
        $this->fGetI = $getDataI;
        $this->fPost = $postData;
        $this->fFiles = $filesData;
        $this->fSession = $sessionData;
        $this->fCookie = $cookiesData;
        $this->fCookieUser = $cookiesDataUser;
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
    
    public function getConnectedUser()
    {
        return $this->connectedUser;
    }
}
