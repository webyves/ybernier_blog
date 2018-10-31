<?php
/***************************************************************** 
file: CommentController.php 
website Comment controller
******************************************************************/
namespace yBernier\Blog\controller;

use \yBernier\Blog\model\manager\CommentManager;

Class CommentController extends PostController
{
    
    /*********************************** 
        Render All Comments  
    ***********************************/
    public function listComments()
    {
        // echo $this->fTwig->render('postComments.twig', array('postList' => $this->postList, 'postListMenu' => $this->postListMenu));
    }
    
    /*********************************** 
        Render 1 specific Comment  
    ***********************************/
    public function comment($idComment)
    {

    }
}