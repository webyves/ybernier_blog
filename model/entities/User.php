<?php
/***************************************************************** 
file: User.php 
Class entities for user
******************************************************************/
namespace yBernier\Blog\model\entities;

Class User
{
    private $iduser;
    private $firstname;
    private $lastname;
    private $email;
    private $password; // CRYPTE
    private $idrole = 0;
    private $role;
    private $idstate;
    private $state;
    
    public function __construct($db_post)
    {
        if(!empty($db_post))
            $this->hydrate($db_post);
       
    }
    
    public function hydrate($data)
    {
        foreach ($data as $attribut => $value) {
            $method = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $attribut)));
            if (is_callable(array($this, $method))) {
                $this->$method($value);
            }
        }
    }

    /* get posts information bloc of functions */
    public function getIduser()
    {
        return $this->iduser;
    }

    public function getFirstname()
    {
        return $this->firstname;
    }

    public function getLastname()
    {
        return $this->lastname;
    }

    public function getEmail()
    {
        return $this->email;
    }
    
    public function getPassword()
    {
        // crypté
        return $this->password;
    }

    public function getIdrole()
    {
        return $this->idrole;
    }

    public function getRole()
    {
        return $this->role;
    }
    
    public function getIdstate()
    {
        return $this->idstate;
    }

    public function getState()
    {
        return $this->state;
    }


    /* Set posts information bloc of functions */
    /* penser a faire des verif pour modif de value */
    public function setIduser($value)
    {
        $this->iduser = $value;
    }

    public function setFirstname($value)
    {
        $this->firstname = $value;
    }

    public function setLastname($value)
    {
        $this->lastname = $value;
    }

    public function setEmail($value)
    {
        $this->email = $value;
    }
    
    public function setPassword($value)
    {
        // A CRYPTER
        $this->password = $value;
    }

    public function setIdrole($value)
    {
        $this->idrole = $value;
    }

    public function setRole($value)
    {
        $this->role = $value;
    }
    
    public function setIdstate($value)
    {
        $this->idstate = $value;
    }

    public function setState($value)
    {
        $this->state = $value;
    }

}
