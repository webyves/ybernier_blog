<?php
/***************************************************************** 
file: index.php 
router and access point for website
******************************************************************/

require ('controller/frontend.php');

try {
    
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'listPosts':
                // lance la fonction du controlleur pour lister les posts
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
        // affiche la liste des posts (par default)
    }
} catch(Exception $e) {
    $errorMessage = $e->getMessage();
    require('view/frontend/errorView.php');
}