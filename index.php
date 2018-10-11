<?php
/***************************************************************** 
file: index.php 
router and access point for website
******************************************************************/

//TWIG !!!!!
// require_once ('vendor/autoload.php');
// $loader = new Twig_Loader_Array(array(
    // 'index' => 'Hello {{ name }}!',
// ));
// $twig = new Twig_Environment($loader);

// echo $twig->render('index', array('name' => 'Fabien'));



require ('controller/frontend.php');

try {
    
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'listPosts':
                listPosts();
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
        listPosts();
    }
} catch(Exception $e) {
    $errorMessage = $e->getMessage();
    require('view/frontend/errorView.php');
}