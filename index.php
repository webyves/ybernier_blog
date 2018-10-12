<?php
/***************************************************************** 
file: index.php 
router and access point for website
******************************************************************/
use \yBernier\Blog\Autoloader;
use \yBernier\Blog\controller\frontoffice\PostController;

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




try {
    
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'listPosts':
                $controller = new PostController();
                $controller->listPosts();
                break;
            case 'post':
                // check id
                // lance la fonction du controlleur
                break;
            default:
                throw new Exception('Action invalide !');
                break;
        }
    } else {
        $controller = new PostController();
        $controller->listPosts();
    }
} catch(Exception $e) {
    $errorMessage = $e->getMessage();
    require('view/frontoffice/errorView.php');
}