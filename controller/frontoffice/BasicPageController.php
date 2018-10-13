<?php
/***************************************************************** 
file: frontoffice.php 
website frontoffice controler
******************************************************************/
namespace yBernier\Blog\controller\frontoffice;

use \yBernier\Blog\model\manager\PostManager;

Class BasicPageController {
    
    function errorPage($errorText)
    {
        global $twig;
        $postManager = new PostManager();
        $postListMenu = $postManager->getPostsListMenu();
        echo $twig->render('error.twig', array('errorText' => $errorText, 'postListMenu' => $postListMenu));
    }
    
    function contactPage()
    {
        global $twig;
        $postManager = new PostManager();
        $postListMenu = $postManager->getPostsListMenu();
        echo $twig->render('contact.twig', array('postListMenu' => $postListMenu));
    }
    
    function mentionsPage()
    {
        global $twig;
        $postManager = new PostManager();
        $postListMenu = $postManager->getPostsListMenu();
        echo $twig->render('mentions.twig', array('postListMenu' => $postListMenu));
    }
    
    function confidentialitePage()
    {
        global $twig;
        $postManager = new PostManager();
        $postListMenu = $postManager->getPostsListMenu();
        echo $twig->render('confidentialite.twig', array('postListMenu' => $postListMenu));
    }
    
}