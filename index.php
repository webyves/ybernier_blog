<?php
/***************************************************************** 
file: index.php 
router and access point for website
******************************************************************/
use \yBernier\Blog\Autoloader;
use \yBernier\Blog\controller\PostController;
use \yBernier\Blog\controller\PageController;
use \yBernier\Blog\controller\UserController;

//Autoload
require ('Autoloader.php');
Autoloader::register();

//TWIG
require_once ('vendor/autoload.php');
$loader = new Twig_Loader_Filesystem('view/frontoffice');
$twig = new Twig_Environment($loader, array(
    'cache' => false, // 'view/frontend/cache',
    'debug' => true,
));
$twig->addExtension(new Twig_Extension_Debug());

//SESSION INIT !!
session_start();

// CONNEXION
if (isset($_SESSION['userObject'])) {
    $UserConnected =  $_SESSION['userObject'];
} else {
    $UserConnected =  null;
}
$UserController = new UserController();
if (isset($_POST['conexEmail']) && isset($_POST['conexInputPassword'])) {
    // Check Form
    $UserConnected = $UserController->connect($_POST['conexEmail'], $_POST['conexInputPassword']);
    // if (isset($_POST['conexChkbxRemember']))
        // $UserController->generateUserCookie($UserConnected);
// } else {
    //CHECK cookie
    // if (!is_null($UserController->getCookieInfo())) {
        // $UserConnected = $UserController->getCookieInfo();
        // $UserController->generateUserCookie($UserConnected);
    // }
}
$twig->addGlobal('userObject', $UserConnected);

    
//Router
try {
    if (isset($_GET['p'])) {
        switch ($_GET['p']) {
            case 'post':
                if (isset($_GET['i']) && is_numeric($_GET['i'])) {
                    if ($_GET['i'] < 1) {
                        throw new Exception('Post introuvable !');
                        break;
                    }
                    $controller = new PostController();
                    $controller->post($_GET['i']);
                } else {
                    throw new Exception('Post invalide !');
                }
                break;
            case 'contact':
            case 'inscription':
            case 'mentions':
            case 'confidentialite':
                $controller = new PageController();
                $controller->showPage($_GET['p']);
                break;
            default:
                throw new Exception('Page invalide !');
                break;
        }
    } else {
        // CHECK ACTION
        if (isset($_GET['a'])) {
            switch ($_GET['a']) {
                case 'logout':
                    // if (isset($_COOKIE["userIdCookie"])) {
                        // $UserController->destroyUserCookie();
                    // }
                    $UserConnected = null;
                    session_destroy();
                    $twig->addGlobal('userObject', "");
                    break;
                    
                case 'inscription' :    
                    $debugController = new PageController();
                    $debugController->debugPage('_POST', $_POST);
                    // NOT TESTED
                    // $UserController->inscription($_POST);
                    break;
                default :
                    throw new Exception('Action invalide !');
                    break;
            }
        }
        $controller = new PostController();
        $controller->listPosts();
    }
} catch(Exception $e) {
    $errorMessage = $e->getMessage();
    $controller = new PageController();
    $controller->errorPage($errorMessage);
}