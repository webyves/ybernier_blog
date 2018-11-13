<?php
/*****************************************************************
file: App.php
Class for Application informations and methods
******************************************************************/
namespace yBernier\Blog;

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
}
