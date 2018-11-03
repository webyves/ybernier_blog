<?php
/***************************************************************** 
file: index.php 
router and access point for website
******************************************************************/
use \yBernier\Blog\Autoloader;
use \yBernier\Blog\controller\PostController;
use \yBernier\Blog\controller\StaticPageController;
use \yBernier\Blog\controller\UserController;
use \yBernier\Blog\controller\CommentController;

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

//SESSION INIT
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
        $UserConnected = $UserController->getCookieInfo();
        $UserController->generateUserCookie($UserConnected);
    }
}
$twig->addGlobal('userObject', $UserConnected);

    
//Router
try {
    if (isset($_GET['p'])) {
        switch ($_GET['p']) {
            // FRONT OFFICE
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
                $controller = new StaticPageController();
                $controller->showPage($_GET['p']);
                break;
                
            case 'sendContactForm' :    
                $controller = new StaticPageController();
                $controller->contact($_POST);
                break;
            case 'sendInscriptionForm' :    
                $UserController->inscription($_POST);
                break;
            case 'sendCommentForm' :    
                if (isset($_GET['i']) && is_numeric($_GET['i'])) {
                    if ($_GET['i'] < 1) {
                        throw new Exception('Post introuvable !');
                    }
                    $CommentController = new CommentController();
                    $CommentController->addComment($_POST, $UserConnected, $_GET['i']);
                } else {
                    throw new Exception('Post invalide !');
                }
                break;
            case 'logout':
                if (isset($_COOKIE["userIdCookie"])) {
                    echo "plop !!";
                    $UserController->destroyUserCookie();
                }
                $UserConnected = null;
                session_destroy();
                $twig->addGlobal('userObject', "");
                $postController = new PostController();
                $postController->listPosts();
                break;
            // BACK OFFICE

            
            default:
                throw new Exception('Page invalide !');
                break;
        }
    } else {
        $postController = new PostController();
        $postController->listPosts();
    }
} catch(Exception $e) {
    $errorMessage = $e->getMessage();
    $controller = new StaticPageController();
    $controller->errorPage($errorMessage);
}