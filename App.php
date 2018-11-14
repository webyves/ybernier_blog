<?php
/*****************************************************************
file: App.php
Class for Application informations and methods
******************************************************************/
namespace yBernier\Blog;

use \yBernier\Blog\model\entities\User;

class App
{
    // Application version
    const APP_VERSION = "V0.113";
    
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

    private $fGet;
    private $fGetP;
    private $fGetI;
    private $fPost;
    private $fFiles;
    private $fSession;
    private $fCookies;
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
        $getDataI = $getDataP = "";
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
        if (isset($_COOKIES)) {
            $cookiesData = $_COOKIES;
        }

        $this->fGet = $getData;
        $this->fGetP = $getDataP;
        $this->fGetI = $getDataI;
        $this->fPost = $postData;
        $this->fFiles = $filesData;
        $this->fSession = $sessionData;
        $this->fCookies = $cookiesData;
    }
        
    /* GET PARTS */
    public function getFGet() {
        return $this->fGet;
    }
    
    public function getFGetI() {
        return $this->fGetI;
    }
    
    public function getFGetP() {
        return $this->fGetP;
    }
    
    public function getFPost() {
        return $this->fPost;
    }
    
    public function getFFiles() {
        return $this->fFiles;
    }
    
    public function getFSession() {
        return $this->fSession;
    }
    
    public function getFCookies() {
        return $this->fCookies;
    }
    
    public function getConnectedUser() {
        return $this->connectedUser;
    }
    
}
