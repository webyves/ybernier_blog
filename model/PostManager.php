<?php
/***************************************************************** 
file: PostManager.php 
Class model for post
******************************************************************/
namespace yBernier\Blog\Model;
require_once("model/Manager.php");

Class PostManager extends Manager 
{
        
    /* get list of post */
    public function getPosts($nbPosts = 5, $idState = 1)
    {
        // return table with title date and small content
        $db = $this->dbConnect();
        $reqPostsList = 'SELECT 
                    id_post, 
                    title, 
                    content, 
                    DATE_FORMAT(date, \'%d/%m/%Y à %Hh%imin%ss\') as date_fr,
                    image_top,
                    id_cat,
                    id_user
                FROM yb_blog_posts 
                WHERE id_state = :id_state 
                ORDER BY date DESC 
                LIMIT 0, :limit';
        $req = $db->prepare($reqPostsList);
        $req->bindValue('limit', $nbPosts, \PDO::PARAM_INT);
        $req->bindValue('id_state', $idState, \PDO::PARAM_INT);
        $req->execute();
        $res = $req->fetchall();
        return $res;
                    
    }

    /* get content of post */
    public function getPost($idPost)
    {
        // return full content 
    }

    /* Set posts information bloc of functions */
    public function setPostCat($idPost, $idCat)
    {
        
    }

    public function setPostImage($idPost, $image)
    {
        
    }

    public function setPostContent($idPost, $content)
    {
        
    }

    public function setPostState($idPost, $idState)
    {
        
    }

    public function setPostTitle($idPost, $title)
    {
        
    }

    public function setPostUser($idPost, $idUser)
    {
        
    }

    public function setPostDate($idPost)
    {
        
    }
}
