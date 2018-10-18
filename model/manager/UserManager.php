<?php
/***************************************************************** 
file: UserManager.php 
Class model for user
******************************************************************/
namespace yBernier\Blog\model\manager;

use \yBernier\Blog\model\entities\User;

Class UserManager extends Manager 
{

    public function getUser($idUser)
    {
        $db = $this->dbConnect();
        $reqPost = 'SELECT 
                    U.id_user as iduser,
                    U.first_name as firstname,
                    U.last_name as lastname,
                    U.email,
                    U.password,
                    
                    U.id_role as idrole,
                    UR.text as role,
                    
                    U.id_state as idstate,
                    US.text as state
                    
                FROM yb_blog_users as U
                LEFT JOIN yb_blog_user_role as UR ON (U.id_role = UR.id_role)
                LEFT JOIN yb_blog_user_state as US ON (U.id_state = US.id_state)
                WHERE U.id_user = :id_user';
        $req = $db->prepare($reqPost);
        $req->bindValue('id_user', $idUser, \PDO::PARAM_INT);
        $req->execute();
        $res = $req->fetch();
        $obj = new User($res);

        return $obj;
    }
    
    
}
