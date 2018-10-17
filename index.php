<?php
/***************************************************************** 
file: index.php 
router and access point for website
******************************************************************/
use \yBernier\Blog\Autoloader;
use \yBernier\Blog\controller\PostController;
use \yBernier\Blog\controller\PageController;

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
                $controller = new PageController();
                $controller->showPage('contact');
                break;
            case 'mentions':
                $controller = new PageController();
                $controller->showPage('mentions');
                break;
            case 'confidentialite':
                $controller = new PageController();
                $controller->showPage('confidentialite');
                break;
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
    $controller = new PageController();
    $controller->errorPage($errorMessage);
}