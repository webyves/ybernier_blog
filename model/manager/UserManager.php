<?php
/***************************************************************** 
file: UserManager.php 
Class model for user
******************************************************************/
namespace yBernier\Blog\model\manager;

use \yBernier\Blog\model\entities\User;

Class UserManager extends Manager 
{

    public function getUser($idUser = "", $email = "")
    {
        $whereVar = "";
        if (!empty($idUser)) {
            $whereVar = "U.id_user = :id_user";
            $param = array(':id_user' => $idUser);
        } elseif (!empty($email)) {
            $whereVar = "U.email = :email";
            $param = array(':email' => $email);
        }
        
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
                WHERE '.$whereVar;
        $req = $db->prepare($reqPost);
        $req->execute($param);
        $res = $req->fetch();
        $obj = new User($res);

        return $obj;
    }
    
    public function addUser($userInfo)
    {
        // NOT TESTED
        // $param = array( ':id_user' => $userInfo,
                        // ':first_name' => $userInfo,
                        // ':last_name' => $userInfo,
                        // ':email' => $userInfo,
                        // ':password' => $userInfo,
                        // ':id_role' => $userInfo,
                        // ':id_state' => $userInfo
        // );

        // $db = $this->dbConnect();
        // $reqPost = 'INSERT INTO yb_blog_users
                        // (id_user, first_name, last_name, email, password, id_role, id_state) 
                        // VALUES
                        // (:id_user, :first_name, :last_name, :email, :password, :id_role, :id_state)';
        // $req = $db->prepare($reqPost);
        // $req->execute($param);
    }
    
}
