<?php
/***************************************************************** 
file: PostManager.php 
Class model for post
******************************************************************/
namespace yBernier\Blog\model\entities;

Class Post
{
    private $title;
    private $content;
    private $datefr;
    private $imagetop;
    private $state;
    private $category;
    private $author;
    
    public function __construct($db_post)
    {
        if(!empty($db_post))
            $this->hydrate($db_post);
       
    }
    
    public function hydrate($data)
    {
        foreach ($data as $attribut => $value)
        {
            $method = 'set'.str_replace(' ', '', ucwords(str_replace('_', ' ', $attribut)));
                 
            if (is_callable(array($this, $method)))
            {
                $this->$method($value);
            }
        }
    }

    /* get posts information bloc of functions */
    public function getCategory()
    {
        return $this->category;
    }

    public function getImagetop()
    {
        return $this->imagetop;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getState()
    {
        return $this->state;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function getDatefr()
    {
        return $this->datefr;
    }
    /* Set posts information bloc of functions */
    /* penser a faire des verif pour modif de value */
    public function setCategory($value)
    {
       $this->category = $value;
    }

    public function setImagetop($value)
    {
       $this->imagetop = $value;
    }

    public function setContent($value)
    {
       $this->content = $value;
    }

    public function setState($value)
    {
       $this->state = $value;
    }

    public function setTitle($value)
    {
       $this->title = $value;
    }

    public function setAuthor($value)
    {
       $this->author = $value;
    }

    public function setDatefr($value)
    {
       $this->datefr = $value;
    }
    
}
