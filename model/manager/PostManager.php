<?php
/***************************************************************** 
file: PostManager.php 
Class model for post
******************************************************************/
namespace yBernier\Blog\model\manager;

use \yBernier\Blog\model\entities\Post;

Class PostManager extends Manager 
{

    public function getPosts($mode = 'menu', $nbPosts = 50, $idState = 1)
    {
        $userReqVar = ""; // for next implementation WHERE user variable
        $catReqVar = ""; // for next implementation WHERE categorie variable
        $orderReqVar = "PC.text, "; 
        if ($mode == 'full_list')
            $orderReqVar=""; 

        
        $db = $this->dbConnect();
        $reqPostsList = 'SELECT 
                    P.id_post as idpost, 
                    P.title, 
                    P.content, 
                    DATE_FORMAT(P.date, \'%d/%m/%Y à %Hh%i\') as datefr,
                    P.image_top as imagetop,
                    
                    P.id_state as idstate,
                    PS.text as state,
                    
                    P.id_cat as idcat,
                    PC.text as category,
                    
                    P.id_user as iduser,
                    CONCAT(U.first_name, " ", U.last_name) as author
                    
                FROM yb_blog_posts as P
                LEFT JOIN yb_blog_users as U ON (P.id_user = U.id_user)
                LEFT JOIN yb_blog_post_category as PC ON (P.id_cat = PC.id_cat)
                LEFT JOIN yb_blog_post_state as PS ON (P.id_state = PS.id_state)
                WHERE P.id_state = :id_state'.$userReqVar.$catReqVar.'
                ORDER BY '.$orderReqVar.'P.date DESC 
                LIMIT 0, :limit';
        $req = $db->prepare($reqPostsList);
        $req->bindValue('limit', $nbPosts, \PDO::PARAM_INT);
        $req->bindValue('id_state', $idState, \PDO::PARAM_INT);
        $req->execute();
        $res = $req->fetchall();

        if ($mode == 'full_list') {
            // for post list page
            $tab = array();
            foreach ($res as $res_post) {
                $obj = new Post($res_post);
                array_push($tab,$obj);
            }
        } elseif ($mode == 'menu') {
            // for leftbar Menu
            $tab = array();
            $n=0;
            foreach ($res as $res_post) {
                $n++;
                $obj = new Post($res_post);
                $tab[$res_post['category']][$n] = $obj;
            }
        }
        
        return $tab;
    }
    
    public function getPost($idPost)
    {
        $db = $this->dbConnect();
        $reqPost = 'SELECT 
                    P.id_post as idpost, 
                    P.title, 
                    P.content, 
                    DATE_FORMAT(P.date, \'%d/%m/%Y à %Hh%i\') as datefr,
                    P.image_top as imagetop,
                    P.id_cat as idcat,
                    P.id_user as iduser,
                    PC.text as category,
                    CONCAT(U.first_name, " ", U.last_name) as author
                FROM yb_blog_posts as P
                LEFT JOIN yb_blog_users as U ON (P.id_user = U.id_user)
                LEFT JOIN yb_blog_post_category as PC ON (P.id_cat = PC.id_cat)
                WHERE P.id_post = :id_post';
        $req = $db->prepare($reqPost);
        $req->bindValue('id_post', $idPost, \PDO::PARAM_INT);
        $req->execute();
        $res = $req->fetch();
        $obj = new Post($res);

        return $obj;
    }
    
    // OBSOLETE
    // public function getPostsListMenu()
    // {
        // $db = $this->dbConnect();
        // $reqPostsList = 'SELECT 
                    // P.id_post as idpost, 
                    // P.title,
                    // P.id_cat as idcat,
                    // PC.text as category
                // FROM yb_blog_posts as P
                // LEFT JOIN yb_blog_post_category as PC ON (P.id_cat = PC.id_cat)
                // WHERE P.id_state = 1 
                // ORDER BY PC.text, P.date DESC
                // LIMIT 0, 50';
        // $req = $db->prepare($reqPostsList);
        // $req->execute();
        // $res = $req->fetchall();

        // $tab = array();
        // $n=0;
        // foreach ($res as $res_post) {
            // $n++;
            // $obj = new Post($res_post);
            // $tab[$res_post['category']][$n] = $obj;
        // }
        // return $tab;
    // }
    
}
