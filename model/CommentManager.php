<?php
/***************************************************************** 
file: CommentManager.php 
model for comments
******************************************************************/
namespace yBernier\Blog\Model;
require_once("model/Manager.php");

Class CommentManager extends Manager
{
    /* get number of comments */
    public function getCommentNb($idPost)
    {
        // return number of comment on post
    }

    /* get content of post */
    public function getComments($idPost, $idComment)
    {
        // return comments for a post or a comment
    }

    /* Set comments information bloc of public functions */
    public function setCommentText($idComment, $text)
    {
        
    }

    public function setCommentPost($idComment, $idPost)
    {
        
    }

    public function setCommentCommentParent($idComment, $idCommentParent)
    {
        
    }

    public function setCommentState($idComment, $idState)
    {
        
    }

    public function setCommentUser($idComment, $idUser)
    {
        
    }
}