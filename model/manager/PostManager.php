<?php
/***************************************************************** 
file: PostManager.php 
Class model for post
******************************************************************/
namespace yBernier\Blog\model\manager;

use \yBernier\Blog\model\entities\Post;

Class PostManager extends Manager 
{

    /*********************************** 
        Function to get multiple post in DB
        
        $mode => leftbar (default) or classic list
        $nbPosts => can restrict quantity
        $idState => can specify state of post
    ***********************************/
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
                    CONCAT(U.first_name, " ", U.last_name) as author,
                    
                    COUNT(C.id_com) as nbcom
                    
                FROM yb_blog_posts as P
                LEFT JOIN yb_blog_users as U ON (P.id_user = U.id_user)
                LEFT JOIN yb_blog_post_category as PC ON (P.id_cat = PC.id_cat)
                LEFT JOIN yb_blog_post_state as PS ON (P.id_state = PS.id_state)
                LEFT JOIN yb_blog_comments as C ON (P.id_post = C.id_post AND C.id_state = 1)
                WHERE P.id_state = :id_state'.$userReqVar.$catReqVar.'
                GROUP BY P.id_post 
                ORDER BY '.$orderReqVar.'P.date DESC 
                LIMIT 0, :limit';
        $req = $db->prepare($reqPostsList);
        $req->bindValue('limit', $nbPosts, \PDO::PARAM_INT);
        $req->bindValue('id_state', $idState, \PDO::PARAM_INT);
        $req->execute();
        $res = $req->fetchall();

        $tab = array();
        if ($mode == 'full_list') {
            // for post list page
            foreach ($res as $res_post) {
                $obj = new Post($res_post);
                array_push($tab,$obj);
            }
        } elseif ($mode == 'menu') {
            // for leftbar Menu
            $n=0;
            foreach ($res as $res_post) {
                $n++;
                $obj = new Post($res_post);
                $tab[$res_post['category']][$n] = $obj;
            }
        }
        if (empty($tab)) {
            throw new \Exception('Aucun posts disponibles');
        } else {
            return $tab;
        }
    }
    
    /*********************************** 
        Function to get 1 specific post in DB  
    ***********************************/
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
        
        if (empty($res)) {
            throw new \Exception('Post Inéxistant !');
        } else {
            $obj = new Post($res);
            return $obj;
        }
    }

    /*********************************** 
        Function to get all categories post in DB  
    ***********************************/
    public function getCats()
    {
        $db = $this->dbConnect();
        $reqPost = '
                SELECT 
                    PC.id_cat as idcat,
                    PC.text as cattext,
                    COUNT(P.id_post) as nbpost
                FROM yb_blog_post_category as PC
                LEFT JOIN yb_blog_posts as P ON (P.id_cat = PC.id_cat)
                GROUP BY PC.id_cat
                ORDER BY PC.text';
        $req = $db->prepare($reqPost);
        $req->execute();
        $res = $req->fetchall();
        return $res;
    }
    
    /*********************************** 
        Function to update 1 category post in DB by id_cat 
    ***********************************/
    public function updateCat($tab)
    {
        $param = array(
            ':id_cat' => $tab['idcat'],
            ':text' => $tab['text']
            );
        $db = $this->dbConnect();
        $reqPost = '
                UPDATE yb_blog_post_category  
                SET text = :text
                WHERE id_cat = :id_cat';
        $req = $db->prepare($reqPost);
        $res = $req->execute($param);
        
        if (!$res)
            throw new \Exception('Erreur lors de la mise à jour !!');
    }
    
    /*********************************** 
        Function to add category post in DB 
    ***********************************/
    public function addCat($tab)
    {
        $param = array(
            ':text' => $tab['text']
            );
        $db = $this->dbConnect();
        $reqPost = '
                INSERT INTO yb_blog_post_category  
                (text) VALUES (:text)';
        $req = $db->prepare($reqPost);
        $res = $req->execute($param);
        
        if (!$res)
            throw new \Exception('Erreur lors de l\'ajout !!');
    }
    
    /*********************************** 
        Function to delete 1 category post in DB by id_cat 
    ***********************************/
    public function deleteCat($idCat)
    {
        if (is_numeric($idCat) && $idCat > 0) {
            $param = array('idcat' => $idCat);
            $db = $this->dbConnect();
            $reqPost = '
                    DELETE FROM yb_blog_post_category  
                    WHERE id_cat = :idcat';
            $req = $db->prepare($reqPost);
            $res = $req->execute($param);
        } else {
            throw new \Exception('Erreur dans la categorie !!');
        }
    }
    
    /*********************************** 
        Function to Change post's category
        $tab = array (
            'newcat' => 1,
            'oldcat' => $post['catModifModalIdCat'],
            'idpost' => 'all' // or one id_post
            );
    ***********************************/
    public function changePostCat($tab)
    {
        if (is_numeric($tab['newcat']) && $tab['newcat'] > 0
        && is_numeric($tab['oldcat']) && $tab['oldcat'] > 0) {
            $param = array(
                'newcat' => $tab['newcat'],
                'oldcat' => $tab['oldcat']
                );
        } else {
            throw new \Exception('Erreur dans les categories !!');
        }
        
        $whereReq = "";
        if ($tab['idpost'] != 'all') {
            if (is_numeric($tab['idpost']) && $tab['idpost'] > 0) {
                $whereReq = " AND id_post = :id_post";
                $param['id_post'] = $tab['idpost'];
            } else {
                throw new \Exception('Erreur lors du transfert !!');
            }
        }
        
        $db = $this->dbConnect();
        $reqPost = '
                UPDATE yb_blog_posts  
                SET id_cat = :newcat
                WHERE id_cat = :oldcat'.$whereReq;
        $req = $db->prepare($reqPost);
        $res = $req->execute($param);
        
    }
    
}
