<?php
/***************************************************************** 
file: frontoffice.php 
website frontoffice controler
******************************************************************/
namespace yBernier\Blog\controller\frontoffice;

Class BasicPageController {
    
    function errorPage($errorText)
    {
        global $twig;
        echo $twig->render('error.twig', array('errorText' => $errorText));
    }
    
    function contactPage()
    {
        global $twig;
        echo $twig->render('contact.twig');
    }
    
    function mentionsPage()
    {
        global $twig;
        echo $twig->render('mentions.twig');
    }
    
    function confidentialitePage()
    {
        global $twig;
        echo $twig->render('confidentialite.twig');
    }
    
}