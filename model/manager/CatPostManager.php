<?php
/*****************************************************************
file: CatPostManager.php
model for comments
******************************************************************/
namespace yBernier\Blog\model\manager;

use \yBernier\Blog\model\entities\CatPost;

class CatPostManager extends Manager
{
    /***********************************
        Function to get all categories post in DB
    ***********************************/
    public function getCats()
    {
        $dbObject = $this->dbConnect();
        $reqPost = '
                SELECT 
                    PC.id_cat as idcat,
                    PC.text as cattext,
                    COUNT(P.id_post) as nbpost
                FROM yb_blog_post_category as PC
                LEFT JOIN yb_blog_posts as P ON (P.id_cat = PC.id_cat)
                GROUP BY PC.id_cat
                ORDER BY PC.text';
        $req = $dbObject->prepare($reqPost);
        $req->execute();
        $res = $req->fetchall();
        
        $tab = array();
        foreach ($res as $res_catpost) {
            $obj = new CatPost($res_catpost);
            array_push($tab, $obj);
        }
        if (empty($tab)) {
            throw new \Exception('Aucune Catégories disponibles');
        } else {
            return $tab;
        }
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
        $dbObject = $this->dbConnect();
        $reqPost = '
                UPDATE yb_blog_post_category  
                SET text = :text
                WHERE id_cat = :id_cat';
        $req = $dbObject->prepare($reqPost);
        $res = $req->execute($param);
        
        if (!$res) {
            throw new \Exception('Erreur lors de la mise à jour !!');
        }
    }
    
    /***********************************
        Function to add category post in DB
    ***********************************/
    public function addCat($tab)
    {
        $param = array(
            ':text' => $tab['text']
            );
        $dbObject = $this->dbConnect();
        $reqPost = '
                INSERT INTO yb_blog_post_category  
                (text) VALUES (:text)';
        $req = $dbObject->prepare($reqPost);
        $res = $req->execute($param);
        
        if (!$res) {
            throw new \Exception('Erreur lors de l\'ajout !!');
        }
    }
    
    /***********************************
        Function to delete 1 category post in DB by id_cat
    ***********************************/
    public function deleteCat($idCat)
    {
        if (is_numeric($idCat) && $idCat > 0) {
            $param = array('idcat' => $idCat);
            $dbObject = $this->dbConnect();
            $reqPost = '
                    DELETE FROM yb_blog_post_category  
                    WHERE id_cat = :idcat';
            $req = $dbObject->prepare($reqPost);
            $res = $req->execute($param);

            if (!$res) {
                throw new \Exception('Erreur lors de la suppression !!');
            }
        } else {
            throw new \Exception('Erreur dans la categorie !!');
        }
    }
}
