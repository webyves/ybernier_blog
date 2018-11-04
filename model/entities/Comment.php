<?php
/***************************************************************** 
file: Comment.php 
Class entities for comment
******************************************************************/
namespace yBernier\Blog\model\entities;

Class Comment
{
    private $idcom;
    private $textcom;
    private $datecom;
    private $idpost;
    private $iduser;
    private $author;
    private $idcomparent;
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

    /* get comments information bloc of functions */
    public function getIdcom()
    {
       return $this->idcom;
    }
    
    public function getTextcom()
    {
       return $this->textcom;
    }
    public function getDatecom()
    {
       return $this->datecom;
    }
    
    public function getIdpost()
    {
       return $this->idpost;
    }
    
    public function getIduser()
    {
       return $this->iduser;
    }
    
    public function getAuthor()
    {
       return $this->author;
    }
    
    public function getIdcomparent()
    {
       return $this->idcomparent;
    }
    
    public function getIdstate()
    {
       return $this->idstate;
    }

    public function getState()
    {
       return $this->state;
    }

    /* Set comments information bloc of public functions */
    public function setIdcom($value)
    {
       $this->idcom = $value;
    }
    
    public function setTextcom($value)
    {
       $this->textcom = $value;
    }
    public function setDatecom($value)
    {
       $this->datecom = $value;
    }
    
    public function setIdpost($value)
    {
       $this->idpost = $value;
    }
    
    public function setIduser($value)
    {
       $this->iduser = $value;
    }
    
    public function setAuthor($value)
    {
       $this->author = $value;
    }
    
    public function setIdcomparent($value)
    {
       $this->idcomparent = $value;
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