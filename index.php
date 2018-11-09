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

//Configuration file
require ('config.php');

//Autoload
require ('Autoloader.php');
Autoloader::register();

//TWIG
require_once ('vendor/autoload.php');
$loader = new Twig_Loader_Filesystem('view');
$twig = new Twig_Environment($loader, array(
    'cache' => false, // 'view/frontend/cache',
    'debug' => true,
));
$twig->addExtension(new Twig_Extension_Debug());
$twig->addGlobal('appVersion', $GLOBALS["appVersion"]);
$twig->addGlobal('captchaSiteKey', $GLOBALS["siteKey"]);

//SESSION INIT
session_start();

try {
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
            case 'admin':
            case 'adminAddPost':
                $controller = new StaticPageController();
                $controller->showAdminPage($_GET['p']);
                break;
                
            case 'adminPosts':
                $controller = new PostController();
                $controller->showAdminPostsPage();
                break;
            case 'sendAdminPostModifForm':    
                $controller = new PostController();
                $controller->modifPost($_POST);
                break;
            case 'adminEditPost':
                $controller = new PostController();
                $controller->showAdminEditPostPage($_GET['i']);
                break;
            case 'sendAdminPostFullModifForm':
                $controller = new PostController();
        // $debug = new StaticPageController();
        // $debug->debugPage($_FILES);
        
                $controller->editPost($_POST, $_FILES, $_GET['i']);
                break;
                
            case 'adminCatPosts':
                $controller = new PostController();
                $controller->showAdminCatPostList();
                break;
            case 'sendAdminCatAddForm':
                $controller = new PostController();
                $controller->newCat($_POST);
                break;
            case 'sendAdminCatModifForm':
                $controller = new PostController();
                $controller->modifCat($_POST);
                break;
            case 'sendAdminCatSupForm':
                $controller = new PostController();
                $controller->supCat($_POST);
                break;
                
            case 'adminComments':
                $controller = new CommentController();
                $controller->showAdminCommentList();
                break;
            case 'sendAdminCommentModifForm':
                $controller = new CommentController();
                $controller->modifComment($_POST);
                break;
                
            case 'adminUsers':
                $UserController->showAdminUserList();
                break;
            case 'sendAdminUserModifForm':
                $UserController->modifUser($_POST);
                break;
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