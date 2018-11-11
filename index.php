<?php
/*****************************************************************
file: index.php
router and access point for website
******************************************************************/
use \yBernier\Blog\Autoloader;
use \yBernier\Blog\App;
use \yBernier\Blog\controller\PostController;
use \yBernier\Blog\controller\StaticPageController;
use \yBernier\Blog\controller\UserController;
use \yBernier\Blog\controller\CommentController;

//Autoload
require('Autoloader.php');
Autoloader::register();

//TWIG
require_once('vendor/autoload.php');
$loader = new Twig_Loader_Filesystem('view');
$twig = new Twig_Environment($loader, array(
    'cache' => false, // 'view/cache',
    'debug' => true,
));
$twig->addExtension(new Twig_Extension_Debug());
$twig->addGlobal('appVersion', App::APP_VERSION);
$twig->addGlobal('captchaSiteKey', App::CAPTCHA_SITE_KEY);

//SESSION INIT
session_start();

try {
    // CONNEXION
    $UserController = new UserController($twig);
    if (isset($_SESSION['userObject']) && !is_null($_SESSION['userObject']->getEmail())) {
        $UserConnected =  $_SESSION['userObject'];
    } else {
        if (isset($_POST['conexEmail']) && isset($_POST['conexInputPassword'])) {
            $UserConnected = $UserController->connect($_POST['conexEmail'], $_POST['conexInputPassword']);
            if (isset($_POST['conexChkbxRemember'])) {
                $UserController->generateUserCookie($UserConnected);
            }
        } elseif (!is_null($UserController->getCookieInfo())) {
            $UserConnected = $UserController->getCookieInfo();
            $UserController->generateUserCookie($UserConnected);
        } else {
            $UserConnected =  $UserController->logout();
        }
    }
    $twig->addGlobal('userObject', $UserConnected);
    
    //Router
    if (isset($_GET['p'])) {
        switch ($_GET['p']) {
            // FRONT OFFICE
            case 'post':
                $controller = new PostController($twig);
                $controller->post($_GET['i']);
                break;
            case 'contact':
            case 'inscription':
            case 'mentions':
            case 'confidentialite':
                $controller = new StaticPageController($twig);
                $controller->showPage($_GET['p']);
                break;
                
            case 'sendContactForm':
                $controller = new StaticPageController($twig);
                $controller->contact($_POST);
                break;
            case 'sendInscriptionForm':
                $controller = new UserController($twig);
                $controller->inscription($_POST);
                break;
            case 'sendCommentForm':
                $CommentController = new CommentController($twig);
                $CommentController->addComment($_POST, $UserConnected, $_GET['i']);
                break;
            case 'logout':
                $controller = new UserController($twig);
                $UserConnected =  $controller->logout(true);
                $twig->addGlobal('userObject', $UserConnected);
                $postController = new PostController($twig);
                $postController->listPosts();
                break;
                
            // BACK OFFICE
            case 'admin':
                $controller = new StaticPageController($twig);
                $controller->showAdminPage($_GET['p']);
                break;
                
            case 'adminPosts':
                $controller = new PostController($twig);
                $controller->showAdminPostsPage();
                break;
            case 'sendAdminPostModifForm':
                $controller = new PostController($twig);
                $controller->modifPost($_POST);
                break;
            case 'adminEditPost':
                $controller = new PostController($twig);
                $controller->showAdminEditPostPage($_GET['i']);
                break;
            case 'sendAdminPostFullModifForm':
                $controller = new PostController($twig);
                $controller->editPost($_POST, $_FILES, $_GET['i']);
                break;
                
            case 'adminAddPost':
                $controller = new PostController($twig);
                $controller->showAdminAddPostPage();
                break;
            case 'sendAdminAddPostForm':
                $controller = new PostController($twig);
                $controller->addPost($_POST, $_FILES);
                break;
        
            case 'adminCatPosts':
                $controller = new PostController($twig);
                $controller->showAdminCatPostList();
                break;
            case 'sendAdminCatAddForm':
                $controller = new PostController($twig);
                $controller->newCat($_POST);
                break;
            case 'sendAdminCatModifForm':
                $controller = new PostController($twig);
                $controller->modifCat($_POST);
                break;
            case 'sendAdminCatSupForm':
                $controller = new PostController($twig);
                $controller->supCat($_POST);
                break;
                
            case 'adminComments':
                $controller = new CommentController($twig);
                $controller->showAdminCommentList();
                break;
            case 'sendAdminCommentModifForm':
                $controller = new CommentController($twig);
                $controller->modifComment($_POST);
                break;
                
            case 'adminUsers':
                $controller = new UserController($twig);
                $controller->showAdminUserList();
                break;
            case 'sendAdminUserModifForm':
                $controller = new UserController($twig);
                $controller->modifUser($_POST);
                break;
            default:
                throw new Exception('Page invalide !');
                break;
        }
    } else {
        $postController = new PostController($twig);
        $postController->listPosts();
    }
} catch (Exception $e) {
    $errorMessage = $e->getMessage();
    $controller = new StaticPageController($twig);
    $controller->errorPage($errorMessage);
}
