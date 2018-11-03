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
            check if response comments or just new comments
            send correct infos to comment manager
    ***********************************/
    public function addComment($post, $UserConnected, $idPost) 
    {
        $idComParent = null;
        $textCom = "";
        
        if (isset($post['respComInputIdCom'])) {
            if (is_numeric($post['respComInputIdCom']) && $post['respComInputIdCom'] > 0) {
                $textCom = $post['respComInputText'];
                $idComParent = $post['respComInputIdCom'];
            } else {
                throw new \Exception('Commentaire invalide !');
            }
        } elseif (isset($post['comInputText'])) {
            $textCom = $post['comInputText'];
        }
        
        if (!empty($textCom)) {
            $commentManager = new CommentManager();
            $commentManager->addComment($textCom, $UserConnected->getIduser(), $idPost, $idComParent);
            $nbcom = $commentManager->getCommentNb($idPost);
            $comments = $commentManager->getComments($idPost);
            
            $postManager = new PostManager();
            $post = $postManager->getPost($idPost);
            
            echo $this->fTwig->render('postCommentsConfirm.twig', array('nbcom' => $nbcom, 'comments' => $comments, 'post' => $post, 'postListMenu' => $this->postListMenu));
        } else {
            $this->post($idPost);
        }
        
    }
}