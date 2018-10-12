<?php
/***************************************************************** 
file: index.php 
router and access point for website
******************************************************************/

require ('controller/frontoffice.php');

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
    require('view/frontoffice/errorView.php');
}