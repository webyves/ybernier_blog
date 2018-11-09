<?php
/***************************************************************** 
file: UserManager.php 
Class model for user
******************************************************************/
namespace yBernier\Blog\model\manager;

use \yBernier\Blog\model\entities\User;

Class UserManager extends Manager 
{
    /*********************************** 
        Function to get user in DB
            can be form id, email, or cookie
    ***********************************/
    public function getUser($idUser = "", $email = "", $cookieid = "")
    {
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
                WHERE ';
        if (!empty($idUser)) {
            $reqPost .= "U.id_user = :id_user";
            $param = array(':id_user' => $idUser);
        } elseif (!empty($email)) {
            $reqPost .= "U.email = :email";
            $param = array(':email' => $email);
        } elseif (!empty($cookieid)) {
            $reqPost .= "U.cookie_id = :cookie_id";
            $param = array(':cookie_id' => $cookieid);
        } else {
            throw new \Exception('ERROR GET USER');
        }
        
        $db = $this->dbConnect();
        $req = $db->prepare($reqPost);
        $req->execute($param);
        $res = $req->fetch();
        $obj = new User($res);

        return $obj;
    }
    
    /*********************************** 
        Private Method to crypt information for user 
        (password or cookie id)
    ***********************************/
    private function cryptInfo($value)
    {
        $valueHashed = password_hash($value, PASSWORD_DEFAULT);
        return $valueHashed;
    }
    
    /*********************************** 
        Function to Insert user in DB
        
        $userInfo = array(
                        'firstName' => "",
                        'lastName' => "",
                        'eMail' => "",
                        'password' => ""    // will be crypted
            );
    ***********************************/
    public function addUser($userInfo)
    {
        $param = array( ':first_name' => $userInfo['firstName'],
                        ':last_name' => $userInfo['lastName'],
                        ':email' => $userInfo['eMail'],
                        ':password' => $this->cryptInfo($userInfo['password']),
                        ':cookie_id' => $this->cryptInfo(microtime()),
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

    /*********************************** 
        Function to get all users in DB
    ***********************************/
    public function getUsers()
    {
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
                ORDER BY U.id_state DESC, U.id_role DESC, U.last_name ASC, U.first_name ASC, U.id_user ASC';
        $req = $db->prepare($reqPost);
        $req->execute();
        $res = $req->fetchall();
        $tab = array();
        foreach ($res as $user) {
            $obj = new User($user);
            array_push($tab,$obj);
        }

        if (empty($tab)) {
            throw new \Exception('Aucun Utilisateurs !!');
        } else {
            return $tab;
        }
    }
    
    /*********************************** 
        Function to get all user's States in DB
    ***********************************/
    public function getStateList()
    {
        $db = $this->dbConnect();
        $reqPost = '
                SELECT 
                    US.id_state as idstate,
                    US.text as state
                FROM yb_blog_user_state as US
                ORDER BY US.text';
        $req = $db->prepare($reqPost);
        $req->execute();
        $res = $req->fetchall();
        return $res;
    }
    
    /*********************************** 
        Function to get all user's roles in DB
    ***********************************/
    public function getRoleList()
    {
        $db = $this->dbConnect();
        $reqPost = '
                SELECT 
                    UR.id_role as idrole,
                    UR.text as role
                FROM yb_blog_user_role as UR
                ORDER BY UR.text';
        $req = $db->prepare($reqPost);
        $req->execute();
        $res = $req->fetchall();
        return $res;
    }
    
    /*********************************** 
        Function to update user infos in DB by id_user
    ***********************************/
    public function updateUser($tab)
    {
        $reqVarUpdate = "";
        $param = array(':id_user' => $tab['iduser']);
        
        if (isset($tab['idstate'])){
            if (!empty($reqVarUpdate))
                $reqVarUpdate .= ", ";
            $reqVarUpdate .= "id_state = :id_state";
            $param['id_state'] = $tab['idstate'];
        }
        if (isset($tab['idrole'])){
            if (!empty($reqVarUpdate))
                $reqVarUpdate .= ", ";
            $reqVarUpdate .= "id_role = :id_role";
            $param['id_role'] = $tab['idrole'];
        }

        $db = $this->dbConnect();
        $reqPost = '
                UPDATE yb_blog_users  
                SET '.$reqVarUpdate.' 
                WHERE id_user = :id_user';
        $req = $db->prepare($reqPost);
        $res = $req->execute($param);
        
        if (!$res)
            throw new \Exception('Erreur lors de la mise à jour !!');
    }
    
    
}
