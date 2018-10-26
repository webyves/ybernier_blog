<?php
/***************************************************************** 
file: PostController.php 
website Post controller
******************************************************************/
namespace yBernier\Blog\controller;

use \yBernier\Blog\model\manager\PostManager;

Class PostController extends PageController
{
    
    public function listPosts()
    {
        echo $this->fTwig->render('listPosts.twig', array('postList' => $this->postList, 'postListMenu' => $this->postListMenu));

    }

    public function post($idPost)
    {
        $postManager = new PostManager();
        $post = $postManager->getPost($idPost);

        echo $this->fTwig->render('post.twig', array('post' => $post, 'postListMenu' => $this->postListMenu));

    }
}