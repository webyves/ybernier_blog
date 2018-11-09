<?php
/***************************************************************** 
file: config.php 
configuration file for the website.
******************************************************************/


/*** Connexion to database ***/
    $GLOBALS['dbUser'] = "root";
    $GLOBALS['dbUserPwd'] = "";
    $GLOBALS['dbHost'] = "localhost";
    $GLOBALS['dbName'] = "ybernier_blog";

    // $GLOBALS['dbUser'] = "ybernierog83";
    // $GLOBALS['dbUserPwd'] = "kvZ13dlC";
    // $GLOBALS['dbHost'] = "ybernierog83.mysql.db";
    // $GLOBALS['dbName'] = "ybernierog83";

/*** Application version ***/
    $GLOBALS['appVersion'] = "V0.109";
    
/*** Administrator eMail ***/
    $GLOBALS['adminEmail'] = "webyves@hotmail.com";
    
/*** reCaptach Google ***/
    $GLOBALS['siteKey'] = '6LfhH2kUAAAAAMbiHPXUeb1K8818IqINi0h1tCs2'; // on Form
    $GLOBALS['secretKey'] = '6LfhH2kUAAAAAKLIzyNxVbfvHVuTNZ7RU3EwYeXJ'; // on Serveur
    
/*** MAX File Size for upload ***/
    $GLOBALS['maxFileSize'] = 1048576; //octets = 1Mo
    // $GLOBALS['maxwidth'] = 800;
    // $GLOBALS['maxheight'] = 600;

