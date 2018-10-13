<?php
/***************************************************************** 
file: index.php 
router and access point for website
******************************************************************/
use \yBernier\Blog\Autoloader;
use \yBernier\Blog\controller\frontoffice\PostController;
use \yBernier\Blog\controller\frontoffice\BasicPageController;

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

//Router
try {
    if (isset($_GET['p'])) {
        switch ($_GET['p']) {
            case 'listPosts':
                $controller = new PostController();
                $controller->listPosts();
                break;
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
                $controller = new BasicPageController();
                $controller->contactPage();
                break;
            case 'mentions':
                $controller = new BasicPageController();
                $controller->mentionsPage();
                break;
            case 'confidentialite':
                $controller = new BasicPageController();
                $controller->confidentialitePage();
                break;
            case 'connexion':
                // break;
            default:
                throw new Exception('Page invalide !');
                break;
        }
    } else {
        $controller = new PostController();
        $controller->listPosts();
    }
} catch(Exception $e) {
    $errorMessage = $e->getMessage();
    $controller = new BasicPageController();
    $controller->errorPage($errorMessage);
}