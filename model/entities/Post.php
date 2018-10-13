<?php
/***************************************************************** 
file: PostManager.php 
Class model for post
******************************************************************/
namespace yBernier\Blog\model\entities;

Class Post
{
    private $idpost;
    private $title;
    private $content;
    private $smallcontent;
    private $datefr;
    private $imagetop;
    private $idstate;
    private $state;
    private $idcat;
    private $category;
    private $iduser;
    private $author;
    
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
    public function getIdpost()
    {
        return $this->idpost;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getSmallontent()
    {
        return $this->content;
    }

    public function getDatefr()
    {
        return $this->datefr;
    }
    
    public function getImagetop()
    {
        return $this->imagetop;
    }

    public function getIdstate()
    {
        return $this->idstate;
    }

    public function getState()
    {
        return $this->state;
    }

    public function getIdcat()
    {
        return $this->idcat;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function getIduser()
    {
        return $this->iduser;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    /* Set posts information bloc of functions */
    /* penser a faire des verif pour modif de value */
    public function setIdpost($value)
    {
       $this->idpost = $value;
    }

    public function setTitle($value)
    {
       $this->title = $value;
    }

    public function setContent($value)
    {
       $this->content = $value;
    }

    public function setSmallcontent($value)
    {
       $this->smallcontent = $value;
    }

    public function setDatefr($value)
    {
       $this->datefr = $value;
    }

    public function setImagetop($value)
    {
       $this->imagetop = $value;
    }

    public function setIdstate($value)
    {
       $this->idstate = $value;
    }

    public function setState($value)
    {
       $this->state = $value;
    }

    public function setIdcat($value)
    {
       $this->idcat = $value;
    }

    public function setCategory($value)
    {
       $this->category = $value;
    }

    public function setIduser($value)
    {
       $this->iduser = $value;
    }

    public function setAuthor($value)
    {
       $this->author = $value;
    }

}
