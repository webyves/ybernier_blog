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
    $UserConnected = $UserController->connect($_POST['conexEmail'], $_POST['conexInputPassword']);
    if (isset($_POST['conexChkbxRemember'])) {
        $UserController->generateUserCookie($UserConnected);
    }
} else {
    if (!is_null($UserController->getCookieInfo())) {
        echo "PLOP !";
        $UserConnected = $UserController->getCookieInfo();
        $UserController->generateUserCookie($UserConnected);
    }
}
$twig->addGlobal('userObject', $UserConnected);
    // $controller = new PageController();
    // $controller->debugPage($_POST);

    
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
        $viewListPost = true;
        if (isset($_GET['a'])) {
            switch ($_GET['a']) {
                case 'logout':
                    if (isset($_COOKIE["userIdCookie"])) {
                        $UserController->destroyUserCookie();
                    }
                    $UserConnected = null;
                    session_destroy();
                    $twig->addGlobal('userObject', "");
                    $viewListPost = true;
                    break;
                    
                case 'inscription' :    
                    $UserController->inscription($_POST);
                    $viewListPost = false;
                    break;
                    
                case 'contact' :    
                    $pageController = new PageController();
                    $pageController->contact($_POST);
                    $viewListPost = false;
                    break;
                default :
                    throw new Exception('Action invalide !');
                    break;
            }
        }
        if ($viewListPost) {
            $postController = new PostController();
            $postController->listPosts();
        }
    }
} catch(Exception $e) {
    $errorMessage = $e->getMessage();
    $controller = new PageController();
    $controller->errorPage($errorMessage);
}