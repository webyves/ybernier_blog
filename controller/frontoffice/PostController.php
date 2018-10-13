<?php
/***************************************************************** 
file: frontoffice.php 
website frontoffice controler
******************************************************************/
namespace yBernier\Blog\controller\frontoffice;

use \yBernier\Blog\model\manager\PostManager;

Class PostController 
{
    
    function listPosts()
    {
        global $twig;

        $postManager = new PostManager();
        $postList = $postManager->getPosts();
        $postListMenu = $postManager->getPostsListMenu();

        echo $twig->render('listPosts.twig', array('postList' => $postList, 'postListMenu' => $postListMenu));

    }

    function post($idPost)
    {
        global $twig;

        $postManager = new PostManager();
        $post = $postManager->getPost($idPost);
        $postListMenu = $postManager->getPostsListMenu();

        echo $twig->render('post.twig', array('post' => $post, 'postListMenu' => $postListMenu));

    }
}