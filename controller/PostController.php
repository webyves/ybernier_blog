<?php
/***************************************************************** 
file: PostController.php 
website Post controller
******************************************************************/
namespace yBernier\Blog\controller;

use \yBernier\Blog\model\manager\PostManager;
use \yBernier\Blog\model\manager\CommentManager;

Class PostController extends PageController
{
    /*********************************** 
        Render All posts  
    ***********************************/
    public function listPosts()
    {
        echo $this->fTwig->render('frontoffice/listPosts.twig', array('postList' => $this->postList, 'postListMenu' => $this->postListMenu));

    }

    /*********************************** 
        Render 1 specific post  
    ***********************************/
    public function post($idPost)
    {
        $postManager = new PostManager();
        $post = $postManager->getPost($idPost);
        
        $commentManager = new CommentManager();
        $nbcom = $commentManager->getCommentNb($idPost);
        $comments = $commentManager->getComments($idPost);

        echo $this->fTwig->render('frontoffice/postComments.twig', array('nbcom' => $nbcom, 'comments' => $comments, 'post' => $post, 'postListMenu' => $this->postListMenu));

    }
}