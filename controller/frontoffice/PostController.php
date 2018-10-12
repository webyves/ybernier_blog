<?php
/***************************************************************** 
file: frontoffice.php 
website frontoffice controler
******************************************************************/
namespace yBernier\Blog\controller\frontoffice;

use \yBernier\Blog\model\manager\PostManager;

Class PostController {
    
    function listPosts()
    {
        global $twig;

        $postManager = new PostManager();
        $postList = $postManager->getPosts();

        echo $twig->render('listPosts.twig', array('postList' => $postList));

        }

    function post($idPost)
    {

    }
}