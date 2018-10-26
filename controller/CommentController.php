<?php
/***************************************************************** 
file: CommentController.php 
website Comment controller
******************************************************************/
namespace yBernier\Blog\controller;

use \yBernier\Blog\model\manager\CommentManager;

Class CommentController extends PostController
{
    
    public function listComments()
    {
        echo $this->fTwig->render('postComments.twig', array('postList' => $this->postList, 'postListMenu' => $this->postListMenu));
    }
}