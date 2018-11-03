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
            SELECT COUNT(id_com) as nbpost
                FROM yb_blog_comments
                WHERE id_state = 1 AND id_post = :id_post';
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
        $orderReqVar = ""; // for next implementation ORDER BY other Things
        if (!is_numeric($idState)) {
            throw new \Exception('ERROR GET STATE COMMENTS');
        }
        if (is_numeric($idPost) && $idPost> 0) {
            $whereVar = " AND C.id_post = :id_post";
            $param[':id_post'] = $idPost;
        } elseif (is_numeric($idComment) && $idComment > 0){
            $whereVar = " AND C.id_com = :id_com";
            $param[':id_com'] = $idComment;
        } else {
            throw new \Exception('ERROR GET COMMENTS');
        }
        
        $db = $this->dbConnect();
        $reqPost = '
            SELECT 
                C.id_com as idcom,
                C.text as textcom,
                DATE_FORMAT(C.date, \'%d/%m/%Y à %Hh%i\') as datecom,
                C.id_post as idpost,
                C.id_com_parent as idcomparent,
                C.id_state as idstate,
                C.id_user as iduser,
                CONCAT(U.first_name, " ", U.last_name) as author
                
            FROM yb_blog_comments as C
            LEFT JOIN yb_blog_users as U ON (C.id_user = U.id_user)
            WHERE C.id_state = '.$idState.$whereVar.' 
            ORDER BY '.$orderReqVar.'C.date DESC ';
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
                        ':id_state' => 1
        );

        $db = $this->dbConnect();
        $reqPost = 'INSERT INTO yb_blog_comments
                        (text, date, id_post, id_user, id_com_parent, id_state) 
                        VALUES
                        (:text, NOW(), :id_post, :id_user, :id_com_parent, :id_state)';
        $req = $db->prepare($reqPost);
        $req->execute($param);
    }

}