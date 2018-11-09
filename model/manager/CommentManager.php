<?php
/***************************************************************** 
file: CommentManager.php 
model for comments
******************************************************************/
namespace yBernier\Blog\model\manager;

use \yBernier\Blog\model\entities\Comment;

Class CommentManager extends Manager
{
    /*********************************** 
        Function to get number of comment for 1 specific post
    ***********************************/
    public function getCommentNb($idPost)
    {
        $db = $this->dbConnect();
        $reqComNb = '
            SELECT 
                COUNT(C1.id_com) as nbpost
            FROM yb_blog_comments as C1
            INNER JOIN yb_blog_comments as C2 ON (
                C2.id_post = C1.id_post AND
                C2.id_state = 1 AND 
                C2.id_com_parent IS NULL)
            WHERE 
                C1.id_state = 1 AND 
                (C1.id_com = C2.id_com OR C1.id_com_parent = C2.id_com) AND 
                C1.id_post = :id_post';
        $req = $db->prepare($reqComNb);
        $req->bindValue('id_post', $idPost, \PDO::PARAM_INT);
        $req->execute();
        $res = $req->fetch();
        
        return $res['nbpost'];
    }

    /*********************************** 
        Function to get multiple comments 
        for 1 specific post or 1 specific comment
        Generate a specific Tab with parents and child comments
    ***********************************/
    public function getComments($idPost = 0, $idComment = 0, $idState = 1)
    {
        $param = null;
        $param[':id_state'] = (int)$idState;
        $reqPost = '
            SELECT 
                C.id_com as idcom,
                C.text as textcom,
                DATE_FORMAT(C.date, \'%d/%m/%Y à %Hh%i\') as datecom,
                C.id_post as idpost,
                C.id_com_parent as idcomparent,
                C.id_state as idstate,
                CS.text as state,
                C.id_user as iduser,
                CONCAT(U.first_name, " ", U.last_name) as author
                
            FROM yb_blog_comments as C
            LEFT JOIN yb_blog_users as U ON (C.id_user = U.id_user)
            LEFT JOIN yb_blog_comment_state as CS ON (C.id_state = CS.id_state)
            WHERE C.id_state = :id_state';
            
        if (is_numeric($idPost) && $idPost > 0) {
            $reqPost .= " AND C.id_post = :id_post";
            $param[':id_post'] = $idPost;
        } elseif (is_numeric($idComment) && $idComment > 0){
            $reqPost .= " AND C.id_com = :id_com";
            $param[':id_com'] = $idComment;
        } else {
            throw new \Exception('ERROR GET COMMENTS');
        }
        $reqPost .= " ORDER BY C.date DESC";
        
        $db = $this->dbConnect();
        $req = $db->prepare($reqPost);
        $req->execute($param);
        $res = $req->fetchall();
        $tabcomchild = array();
        $tabcomparent = array();
        foreach ($res as $res_post) {
            if (empty($res_post['idcomparent'])) {
                $obj = new Comment($res_post);
                array_push($tabcomparent,$obj);
            } else {
                $obj = new Comment($res_post);
                if (!isset($tabcomchild[$res_post['idcomparent']])) {
                    $tabcomchild[$res_post['idcomparent']][0] = $obj;
                } else {
                    array_push($tabcomchild[$res_post['idcomparent']],$obj);
                }
            }
        }
        $tab = array('parent' => $tabcomparent, 'child' => $tabcomchild);
        return $tab;
    }
    
    /*********************************** 
        Function to Insert Comment in DB
    ***********************************/
    public function addComment($text, $idUser, $idPost, $idComParent) 
    {
        $param = array( ':text' => strip_tags($text),
                        ':id_post' => $idPost,
                        ':id_user' => $idUser,
                        ':id_com_parent' => $idComParent,
                        ':id_state' => 2
        );

        $db = $this->dbConnect();
        $reqPost = 'INSERT INTO yb_blog_comments
                        (text, date, id_post, id_user, id_com_parent, id_state) 
                        VALUES
                        (:text, NOW(), :id_post, :id_user, :id_com_parent, :id_state)';
        $req = $db->prepare($reqPost);
        $req->execute($param);
    }
    
    
    /*********************************** 
        Function to get All comments 
    ***********************************/
    public function getCommentList()
    {
        $db = $this->dbConnect();
        $reqPost = '
            SELECT 
                C.id_com as idcom,
                C.text as textcom,
                DATE_FORMAT(C.date, \'%d/%m/%Y à %Hh%i\') as datecom,
                C.id_post as idpost,
                C.id_com_parent as idcomparent,
                C.id_state as idstate,
                CS.text as state,
                C.id_user as iduser,
                CONCAT(U.first_name, " ", U.last_name) as author
                
            FROM yb_blog_comments as C
            LEFT JOIN yb_blog_users as U ON (C.id_user = U.id_user)
            LEFT JOIN yb_blog_comment_state as CS ON (C.id_state = CS.id_state)
            ORDER BY C.id_post DESC, C.date DESC';
        $req = $db->prepare($reqPost);
        $req->execute();
        $res = $req->fetchall();
        $tabcomchild = array();
        $tabcomparent = array();
        foreach ($res as $res_post) {
            if (empty($res_post['idcomparent'])) {
                $obj = new Comment($res_post);
                array_push($tabcomparent,$obj);
            } else {
                $obj = new Comment($res_post);
                if (!isset($tabcomchild[$res_post['idcomparent']])) {
                    $tabcomchild[$res_post['idcomparent']][0] = $obj;
                } else {
                    array_push($tabcomchild[$res_post['idcomparent']],$obj);
                }
            }
        }
        $tab = array('parent' => $tabcomparent, 'child' => $tabcomchild);
        return $tab;
    }
    
    /*********************************** 
        Function to get all comment's States in DB
    ***********************************/
    public function getStateList()
    {
        $db = $this->dbConnect();
        $reqPost = '
                SELECT 
                    CS.id_state as idstate,
                    CS.text as state
                FROM yb_blog_comment_state as CS
                ORDER BY CS.text';
        $req = $db->prepare($reqPost);
        $req->execute();
        $res = $req->fetchall();
        return $res;
    }
    
    /*********************************** 
        Function to update comment in DB by id_com
    ***********************************/
    public function updateComment($tab)
    {
        $nbValToSet = 0;
        $param = array(':id_com' => $tab['idcom']);

        $reqPost = '
                UPDATE yb_blog_comments  
                SET ';
        
        if (isset($tab['idstate'])){
            $reqPost .= "id_state = :id_state";
            $param[':id_state'] = (int)$tab['idstate'];
            $nbValToSet++;
        }
        
        $reqPost .= ' WHERE id_com = :id_com';
        
        if ($nbValToSet < 1)
            throw new \Exception('Aucune Valeur pour la mise à jour !!');
                
        $db = $this->dbConnect();
        $req = $db->prepare($reqPost);
        $res = $req->execute($param);
        
        if (!$res)
            throw new \Exception('Erreur lors de la mise à jour !!');
    }
    
}