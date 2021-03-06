<?php
/*****************************************************************
file: PostManager.php
Class model for post
******************************************************************/
namespace yBernier\Blog\model\manager;

use \yBernier\Blog\model\entities\Post;

class PostManager extends Manager
{

    /***********************************
        Function to get multiple post in DB
            $mode => sidebar (default) or classic list
            $nbPosts => can restrict quantity
            $idState => can specify state of post
            $userId => can specify an author of post
            $catId => can specify a category of post
    ***********************************/
    public function getPosts($mode = 'menu', $nbPosts = 50, $idState = 1, $userId = 'all', $catId = 'all')
    {
        $param = null;
        $orderReq = "PC.text, ";
        if ($mode == 'full_list') {
            $orderReq = "";
        }
        if ($mode == 'by_id') {
            $orderReq = "P.id_post, ";
        }
        
        $limitReq = "";
        if ($nbPosts != "all") {
            $limitReq = " LIMIT 0, ".(int)$nbPosts;
        }
        
        $whereReq = "";
        if ($idState != "all") {
            if (!empty($whereReq)) {
                $whereReq .= " AND ";
            }
            $whereReq .= "P.id_state = :id_state";
            $param[':id_state'] = (int)$idState;
        }
        
        if ($userId != "all") {
            if (!empty($whereReq)) {
                $whereReq .= " AND ";
            }
            $whereReq .= "P.id_user = :id_user";
            $param[':id_user'] = (int)$userId;
        }
        
        if ($catId != "all") {
            if (!empty($whereReq)) {
                $whereReq .= " AND ";
            }
            $whereReq .= "P.id_cat = :id_cat";
            $param[':id_cat'] = (int)$catId;
        }
        
        $reqPostsList = 'SELECT 
                    P.id_post as idpost, 
                    P.title, P.content, 
                    DATE_FORMAT(P.date, \'%d/%m/%Y à %Hh%i\') as datefr,
                    P.image_top as imagetop,
                    P.id_state as idstate, PS.text as state,
                    P.id_cat as idcat, PC.text as category,
                    P.id_user as iduser, CONCAT(U.first_name, " ", U.last_name) as author,
                    (SELECT COUNT(C1.id_com)
                    FROM yb_blog_comments as C1
                    INNER JOIN yb_blog_comments as C2 ON (
                        C2.id_post = C1.id_post AND
                        C2.id_state = 1 AND 
                        C2.id_com_parent IS NULL)
                    WHERE 
                        C1.id_state = 1 AND 
                        (C1.id_com = C2.id_com OR C1.id_com_parent = C2.id_com) AND 
                        C1.id_post = P.id_post) as nbcom
                FROM yb_blog_posts as P
                LEFT JOIN yb_blog_users as U ON (P.id_user = U.id_user)
                LEFT JOIN yb_blog_post_category as PC ON (P.id_cat = PC.id_cat)
                LEFT JOIN yb_blog_post_state as PS ON (P.id_state = PS.id_state)';
                
        if (!empty($whereReq)) {
            $reqPostsList .= " WHERE ";
            $reqPostsList .= $whereReq;
        }
        
        $reqPostsList .= '
                GROUP BY P.id_post 
                ORDER BY '.$orderReq.'P.date DESC 
                '.$limitReq;
        
        $dbObject = $this->dbConnect();
        $req = $dbObject->prepare($reqPostsList);
        if ($param !== null) {
            $req->execute($param);
        } else {
            $req->execute();
        }
        $res = $req->fetchall();

        $tab = array();
        if ($mode == 'full_list') {
            foreach ($res as $res_post) {
                $obj = new Post($res_post);
                array_push($tab, $obj);
            }
        } elseif ($mode == 'by_id') {
            foreach ($res as $res_post) {
                $obj = new Post($res_post);
                $tab[$res_post['idpost']] = $obj;
            }
        } elseif ($mode == 'menu') {
            $nbCat = 0;
            foreach ($res as $res_post) {
                $nbCat++;
                $obj = new Post($res_post);
                $tab[$res_post['category']][$nbCat] = $obj;
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
        $dbObject = $this->dbConnect();
        $reqPost = 'SELECT 
                    P.id_post as idpost, 
                    P.title, 
                    P.content, 
                    DATE_FORMAT(P.date, \'%d/%m/%Y à %Hh%i\') as datefr,
                    P.image_top as imagetop,
                    P.id_cat as idcat,
                    P.id_state as idstate,
                    P.id_user as iduser,
                    PC.text as category,
                    CONCAT(U.first_name, " ", U.last_name) as author
                FROM yb_blog_posts as P
                LEFT JOIN yb_blog_users as U ON (P.id_user = U.id_user)
                LEFT JOIN yb_blog_post_category as PC ON (P.id_cat = PC.id_cat)
                WHERE P.id_post = :id_post';
        $req = $dbObject->prepare($reqPost);
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
        Function to get all State post in DB
    ***********************************/
    public function getStates()
    {
        $dbObject = $this->dbConnect();
        $reqPost = '
                SELECT 
                    S.id_state as idstate,
                    S.text as state
                FROM yb_blog_post_state as S
                ORDER BY S.text';
        $req = $dbObject->prepare($reqPost);
        $req->execute();
        $res = $req->fetchall();
        return $res;
    }
    
    /***********************************
        Function to Update post by id_post
        $tab = array(
            'id_post' => '',
            'id_user' => '',
            'title' => '',
            'content' => '',
            'image_top' => '',
            'id_state' => '',
            'id_cat' => ''
            );
    ***********************************/
    public function updatePost($tab)
    {
        
        $nbValToSet = $nbValWhere = 0;
        $param = array();
        
        $reqPost = '
                UPDATE yb_blog_posts
                SET ';
        
        foreach ($tab as $key => $value) {
            if (!empty($value) && $key != 'id_post' && $key != 'id_user') {
                $param[$key] = $value;
                if ($nbValToSet > 0) {
                    $reqPost .= ', ';
                }
                $reqPost .= $key.' = :'.$key;
                $nbValToSet++;
            }
        }
        if ($nbValToSet < 1) {
            throw new \Exception('Aucune Valeur pour la mise à jour !!');
        }

        if (isset($tab['id_post'])) {
            $param[':id_post'] = $tab['id_post'];
            $reqPost .=' WHERE id_post = :id_post';
            $nbValWhere++;
        } elseif (isset($tab['id_user'])) {
            $param[':id_user'] = $tab['id_user'];
            $reqPost .=' WHERE id_user = :id_user';
            $nbValWhere++;
        }
        if ($nbValWhere < 1) {
            throw new \Exception('Erreur lors de la mise à jour !!');
        }
        
        
        $dbObject = $this->dbConnect();
        $req = $dbObject->prepare($reqPost);
        $res = $req->execute($param);
        
        if (!$res) {
            throw new \Exception('Erreur lors de la mise à jour !!');
        }
    }

    /***********************************
        Function to add a post
        $tab = array(
            'title' => '',
            'content' => '', // CAN BE NULL
            'image_top' => '', // CAN BE NULL
            'id_state' => '',
            'id_cat' => ''
            );
    ***********************************/
    public function addPost($tab)
    {
        
        $param = null;
        $reqCol = "";
        $reqVal = "";
        
        foreach ($tab as $key => $value) {
            if (!empty($value) && $key != 'id_post') {
                $param[$key] = $value;
                
                if (!empty($reqCol)) {
                    $reqCol .= ', ';
                }
                $reqCol .= $key;
                
                if (!empty($reqVal)) {
                    $reqVal .= ', ';
                }
                $reqVal .= ':'.$key;
            }
        }
        
        if (!empty($reqCol)) {
            $dbObject = $this->dbConnect();
            $reqPost = '
                    INSERT INTO yb_blog_posts  
                    ('.$reqCol.', date) VALUES ('.$reqVal.', NOW())';
            $req = $dbObject->prepare($reqPost);
            $res = $req->execute($param);
            
            if (!$res) {
                throw new \Exception('Erreur lors l\'ajout du post !!');
            }
            
            $idNewPost = $dbObject->lastInsertId();
            return $idNewPost;
        } else {
                throw new \Exception('Aucune donnée à ajouter');
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
        
        $reqPost = '
                UPDATE yb_blog_posts  
                SET id_cat = :newcat
                WHERE id_cat = :oldcat';
                
        if ($tab['idpost'] != 'all') {
            if (is_numeric($tab['idpost']) && $tab['idpost'] > 0) {
                $reqPost .= " AND id_post = :id_post";
                $param['id_post'] = $tab['idpost'];
            } else {
                throw new \Exception('Erreur lors du transfert !!');
            }
        }
        
        $dbObject = $this->dbConnect();
        $req = $dbObject->prepare($reqPost);
        $req->execute($param);
    }
}
