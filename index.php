<?php
/*****************************************************************
file: index.php
router and access point for website
******************************************************************/
use \yBernier\Blog\Autoloader;
use \yBernier\Blog\App;
use \yBernier\Blog\controller\PostController;
use \yBernier\Blog\controller\CatPostController;
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

// Processing form data for passing
$postData = $filesData = array();
$getDataI = $getDataP = "";
if (isset($_POST)) {
    $postData = $_POST;
}
if (isset($_GET['p'])) {
    $getDataP = $_GET['p'];
}
if (isset($_GET['i'])) {
    $getDataI = $_GET['i'];
}
if (isset($_FILES)) {
    $filesData = $_FILES;
}

try {
    // CONNEXION
    $UserController = new UserController($twig);
    if (isset($_SESSION['userObject']) && !is_null($_SESSION['userObject']->getEmail())) {
        $UserConnected =  $_SESSION['userObject'];
    } else {
        if (isset($postData['conexEmail']) && isset($postData['conexInputPassword'])) {
            $UserConnected = $UserController->connect($postData['conexEmail'], $postData['conexInputPassword']);
            if (isset($postData['conexChkbxRemember'])) {
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
    if (!empty($getDataP)) {
        switch ($getDataP) {
            // FRONT OFFICE BASIC PAGES
            case 'contact':
            case 'inscription':
            case 'mentions':
            case 'confidentialite':
                $controller = new StaticPageController($twig);
                $controller->showPage($getDataP);
                break;
                
            // FRONT OFFICE FORM CALLBACK
            case 'sendContactForm':
                $controller = new StaticPageController($twig);
                $controller->contact($postData);
                break;
            case 'sendInscriptionForm':
                $controller = new UserController($twig);
                $controller->inscription($postData);
                break;
            case 'sendCommentForm':
                $CommentController = new CommentController($twig);
                $CommentController->addComment($postData, $UserConnected, $getDataI);
                break;
                
            // FRONT OFFICE 1 POST PAGE
            case 'post':
                $controller = new PostController($twig);
                $controller->post($getDataI);
                break;
                
            // LOGOUT
            case 'logout':
                $controller = new UserController($twig);
                $UserConnected =  $controller->logout(true);
                $twig->addGlobal('userObject', $UserConnected);
                $postController = new PostController($twig);
                $postController->listPosts();
                break;
                
            // BACK OFFICE HOME
            case 'admin':
                $controller = new StaticPageController($twig);
                $controller->showAdminPage($getDataP);
                break;
                
            // BACK OFFICE POSTS
            case 'adminPosts':
                $controller = new PostController($twig);
                $controller->showAdminPostsPage();
                break;
            case 'sendAdminPostModifForm':
                $controller = new PostController($twig);
                $controller->modifPost($postData);
                break;
            case 'adminEditPost':
                $controller = new PostController($twig);
                $controller->showAdminEditPostPage($getDataI);
                break;
            case 'sendAdminPostFullModifForm':
                $controller = new PostController($twig);
                $controller->editPost($postData, $filesData, $getDataI);
                break;
                
            // BACK OFFICE NEW POSTS
            case 'adminAddPost':
                $controller = new PostController($twig);
                $controller->showAdminAddPostPage();
                break;
            case 'sendAdminAddPostForm':
                $controller = new PostController($twig);
                $controller->addPost($postData, $filesData);
                break;
        
            // BACK OFFICE CAT POSTS
            case 'adminCatPosts':
                $controller = new CatPostController($twig);
                $controller->showAdminCatPostList();
                break;
            case 'sendAdminCatAddForm':
                $controller = new CatPostController($twig);
                $controller->newCat($postData);
                break;
            case 'sendAdminCatModifForm':
                $controller = new CatPostController($twig);
                $controller->modifCat($postData);
                break;
            case 'sendAdminCatSupForm':
                $controller = new CatPostController($twig);
                $controller->supCat($postData);
                break;
                
            // BACK OFFICE COMMENTS
            case 'adminComments':
                $controller = new CommentController($twig);
                $controller->showAdminCommentList();
                break;
            case 'sendAdminCommentModifForm':
                $controller = new CommentController($twig);
                $controller->modifComment($postData);
                break;
                
            // BACK OFFICE USERS
            case 'adminUsers':
                $controller = new UserController($twig);
                $controller->showAdminUserList();
                break;
            case 'sendAdminUserModifForm':
                $controller = new UserController($twig);
                $controller->modifUser($postData);
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
