<?php
/***************************************************************** 
file: PostManager.php 
Class model for post
******************************************************************/
namespace yBernier\Blog\model\manager;

use \yBernier\Blog\model\entities\Post;

Class PostManager extends Manager 
{
    /* get list of post */
    public function getPosts($nbPosts = 5, $idState = 1)
    {
        $db = $this->dbConnect();
        $reqPostsList = 'SELECT 
                    id_post, 
                    title, 
                    content, 
                    DATE_FORMAT(date, \'%d/%m/%Y à %Hh%imin%ss\') as datefr,
                    image_top as imagetop,
                    id_state as state,
                    id_cat as category,
                    id_user as author
                FROM yb_blog_posts 
                WHERE id_state = :id_state 
                ORDER BY date DESC 
                LIMIT 0, :limit';
        $req = $db->prepare($reqPostsList);
        $req->bindValue('limit', $nbPosts, \PDO::PARAM_INT);
        $req->bindValue('id_state', $idState, \PDO::PARAM_INT);
        $req->execute();
        $res = $req->fetchall();

        $tab = array();
        foreach ($res as $res_post){
            $obj = new Post($res_post);
            array_push($tab,$obj);
        }
        return $tab;
    }
}
