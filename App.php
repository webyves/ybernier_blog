<?php
/*****************************************************************
file: App.php
Class for Application informations
and methods for passing form data and encapsulation
******************************************************************/
namespace yBernier\Blog;

use \yBernier\Blog\model\entities\User;

class App
{
/*****************************************************************
                USERS PREFERENCES
        CAN BE MODIFIED MANUALLY AT INSTALLATION
******************************************************************/
    
    // Connexion to database
    const DB_HOST = "localhost";
    const DB_NAME = "ybernier_blog";
    const DB_USER = "root";
    const DB_USER_PWD = "";

    // Administrator eMail
    const ADMIN_EMAIL = "webyves@hotmail.com";
    
    // reCaptach Google
    const CAPTCHA_SITE_KEY = '6LfhH2kUAAAAAMbiHPXUeb1K8818IqINi0h1tCs2'; // on Form
    const CAPTCHA_SECRET_KEY = '6LfhH2kUAAAAAKLIzyNxVbfvHVuTNZ7RU3EwYeXJ'; // on Serveur
    
    // MAX File Size for upload in octects
    const MAX_FILE_SIZE = 1048576; // 1Mo
    
/********* END OF USERS PREFERENCES *********/
    
    const APP_VERSION = "V0.116";   // Application version
    private $fGet;                  // $_GET
    private $fGetP;                 // $_POST
    private $fGetI;                 // $_GET['i']
    private $fPost;                 // $_GET['p']
    private $fFiles;                // $_FILES
    private $fSession;              // $_SESSION
    private $fCookie;              // $_COOKIE
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
