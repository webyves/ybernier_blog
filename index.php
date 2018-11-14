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
require_once('Autoloader.php');
Autoloader::register();
require_once('vendor/autoload.php');

//SESSION INIT
session_start();

// Processing form data for passing
$App = new App();
$postData = $App->getFPost();
$filesData = $App->getFFiles();
$getDataI = $App->getFGetI();
$getDataP = $App->getFGetP();
$sessionData = $App->getFSession();

try {
    // CONNEXION
    $UserController = new UserController($App);
    if (isset($sessionData['userObject']) && !is_null($sessionData['userObject']->getEmail())) {
        $UserConnected =  $sessionData['userObject'];
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
            $UserConnected = $UserController->logout();
        }
    }
    $App->setConnectedUser($UserConnected);
    
    //Router
    if (!empty($getDataP)) {
        switch ($getDataP) {
            // Error Pages
            case 'erreur':
                $controller = new StaticPageController($App);
                $controller->errorPage('', $getDataI);
                break;
                
            // FRONT OFFICE BASIC PAGES
            case 'contact':
            case 'inscription':
            case 'mentions':
            case 'confidentialite':
                $controller = new StaticPageController($App);
                $controller->showPage($getDataP);
                break;
                
            // FRONT OFFICE FORM CALLBACK
            case 'sendContactForm':
                $controller = new StaticPageController($App);
                $controller->contact($postData);
                break;
            case 'sendInscriptionForm':
                $controller = new UserController($App);
                $controller->inscription($postData);
                break;
            case 'sendCommentForm':
                $CommentController = new CommentController($App);
                $CommentController->addComment($postData, $getDataI);
                break;
                
            // FRONT OFFICE 1 POST PAGE
            case 'post':
                $controller = new PostController($App);
                $controller->post($getDataI);
                break;
                
            // LOGOUT
            case 'logout':
                $controller = new UserController($App);
                $UserConnected =  $controller->logout(true);
                $App->setConnectedUser($UserConnected);
                $postController = new PostController($App);
                $postController->listPosts();
                break;
                
            // BACK OFFICE HOME
            case 'admin':
                $controller = new StaticPageController($App);
                $controller->showAdminPage($getDataP);
                break;
                
            // BACK OFFICE POSTS
            case 'adminPosts':
                $controller = new PostController($App);
                $controller->showAdminPostsPage();
                break;
            case 'sendAdminPostModifForm':
                $controller = new PostController($App);
                $controller->modifPost($postData);
                break;
            case 'adminEditPost':
                $controller = new PostController($App);
                $controller->showAdminEditPostPage($getDataI);
                break;
            case 'sendAdminPostFullModifForm':
                $controller = new PostController($App);
                $controller->editPost($postData, $filesData, $getDataI);
                break;
                
            // BACK OFFICE NEW POSTS
            case 'adminAddPost':
                $controller = new PostController($App);
                $controller->showAdminAddPostPage();
                break;
            case 'sendAdminAddPostForm':
                $controller = new PostController($App);
                $controller->addPost($postData, $filesData);
                break;
        
            // BACK OFFICE CAT POSTS
            case 'adminCatPosts':
                $controller = new CatPostController($App);
                $controller->showAdminCatPostList();
                break;
            case 'sendAdminCatAddForm':
                $controller = new CatPostController($App);
                $controller->newCat($postData);
                break;
            case 'sendAdminCatModifForm':
                $controller = new CatPostController($App);
                $controller->modifCat($postData);
                break;
            case 'sendAdminCatSupForm':
                $controller = new CatPostController($App);
                $controller->supCat($postData);
                break;
                
            // BACK OFFICE COMMENTS
            case 'adminComments':
                $controller = new CommentController($App);
                $controller->showAdminCommentList();
                break;
            case 'sendAdminCommentModifForm':
                $controller = new CommentController($App);
                $controller->modifComment($postData);
                break;
                
            // BACK OFFICE USERS
            case 'adminUsers':
                $controller = new UserController($App);
                $controller->showAdminUserList();
                break;
            case 'sendAdminUserModifForm':
                $controller = new UserController($App);
                $controller->modifUser($postData);
                break;
                
            default:
                throw new Exception('Page invalide !');
                break;
        }
    } else {
        $postController = new PostController($App);
        $postController->listPosts();
    }
} catch (Exception $e) {
    $errorMessage = $e->getMessage();
    $controller = new StaticPageController($App);
    $controller->errorPage($errorMessage);
}
