<?php
/***************************************************************** 
file: CommentController.php 
website Comment controller
******************************************************************/
namespace yBernier\Blog\controller;

use \yBernier\Blog\model\manager\CommentManager;
use \yBernier\Blog\model\manager\PostManager;

Class CommentController extends PostController
{
    
    /*********************************** 
        Function for Adding comment 
            check possible error
            send correct infos to comment manager
    ***********************************/
    public function addComment($post, $UserConnected, $idPost, $idComParent = null) 
    {
        if (!empty($post['comInputText'])) {
            $commentManager = new CommentManager();
            $commentManager->addComment($post['comInputText'], $UserConnected->getIduser(), $idPost, $idComParent);
            $nbcom = $commentManager->getCommentNb($idPost);
            $comments = $commentManager->getComments($idPost);
            
            $postManager = new PostManager();
            $post = $postManager->getPost($idPost);
            
            echo $this->fTwig->render('postCommentsConfirm.twig', array('nbcom' => $nbcom, 'comments' => $comments, 'post' => $post, 'postListMenu' => $this->postListMenu));
        } else {
            $this->post($idPost);
        }
    }
    
    /*********************************** 
        Render All Comments  
    ***********************************/
    public function listComments()
    {
        
    }
    
    /*********************************** 
        Render 1 specific Comment  
    ***********************************/
    public function comment($idComment)
    {

    }
}