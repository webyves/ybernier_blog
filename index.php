<?php
/***************************************************************** 
file: index.php 
router and access point for website
******************************************************************/
use \yBernier\Blog\Autoloader;
use \yBernier\Blog\controller\PostController;
use \yBernier\Blog\controller\PageController;
use \yBernier\Blog\model\manager\UserManager;

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

// CHECK CONNEXION
if (isset($_POST) && !empty($_POST)) {
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
} else {
    //CHECK cookie
}
//override for test
$UserObjectManager = new UserManager();
$UserObject = $UserObjectManager->getUser("2");
    echo "<pre>";
    print_r($UserObject);
    echo "</pre>";


//Router
try {
    if (isset($_GET['p'])) {
        switch ($_GET['p']) {
            // case 'listPosts':
                // $controller = new PostController();
                // $controller->listPosts();
                // break;
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
        
        // CHECK ACTION
        if (isset($_GET['a'])) {
            switch ($_GET['a']) {
                case 'logout':
                    // destroy user object and user cookie
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