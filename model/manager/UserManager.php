<?php
/***************************************************************** 
file: UserManager.php 
Class model for user
******************************************************************/
namespace yBernier\Blog\model\manager;

use \yBernier\Blog\model\entities\User;

Class UserManager extends Manager 
{

    public function getUser($idUser = "", $email = "", $cookieid = "")
    {
        $whereVar = "";
        if (!empty($idUser)) {
            $whereVar = "U.id_user = :id_user";
            $param = array(':id_user' => $idUser);
        } elseif (!empty($email)) {
            $whereVar = "U.email = :email";
            $param = array(':email' => $email);
        } elseif (!empty($cookieid)) {
            $whereVar = "U.cookie_id = :cookie_id";
            $param = array(':cookie_id' => $cookieid);
        }
        
        $db = $this->dbConnect();
        $reqPost = 'SELECT 
                    U.id_user as iduser,
                    U.first_name as firstname,
                    U.last_name as lastname,
                    U.email,
                    U.password,
                    U.cookie_id as cookieid,
                    
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
    
    public function cryptPassword($password)
    {
        $passwordHashed = password_hash($password, PASSWORD_DEFAULT);
        return $passwordHashed;
    }
    
    public function addUser($userInfo)
    {
        $param = array( ':first_name' => $userInfo['firstName'],
                        ':last_name' => $userInfo['lastName'],
                        ':email' => $userInfo['eMail'],
                        ':password' => $this->cryptPassword($userInfo['password']),
                        ':cookie_id' => $this->cryptPassword(microtime()),
                        ':id_role' => 4,
                        ':id_state' => 2
        );

        $db = $this->dbConnect();
        $reqPost = 'INSERT INTO yb_blog_users
                        (first_name, last_name, email, password, cookie_id, id_role, id_state) 
                        VALUES
                        (:first_name, :last_name, :email, :password, :cookie_id, :id_role, :id_state)';
        $req = $db->prepare($reqPost);
        $req->execute($param);
    }
    
}
