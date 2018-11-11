<?php
/*****************************************************************
file: CatPost.php
Class entities for post's categories
******************************************************************/
namespace yBernier\Blog\model\entities;

class CatPost
{
    private $idcat;
    private $cattext;
    private $nbpost;

    public function __construct($db_post)
    {
        if (!empty($db_post)) {
            $this->hydrate($db_post);
        }
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
    public function getIdcat()
    {
        return $this->idcat;
    }
    
    public function getCattext()
    {
        return $this->cattext;
    }
    
    public function getNbpost()
    {
        return $this->nbpost;
    }

    /* Set comments information bloc of public functions */
    public function setIdcat($value)
    {
        $this->idcat = $value;
    }
    
    public function setCattext($value)
    {
        $this->cattext = $value;
    }
    
    public function setNbpost($value)
    {
        $this->nbpost = $value;
    }
}
