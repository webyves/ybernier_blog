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
    private $date_fr;
    private $image_top;
    private $state;
    private $category;
    private $author;
    
    public function __construct($db_post){
        //penser a faire des set pour verfier les contenu
        $this->title = $db_post['title'];
        $this->content = $db_post['content'];
        $this->date_fr = $db_post['date_fr'];
        $this->image_top = $db_post['image_top'];
        // a changer via jointure
        $this->state = $db_post['id_state'];
        $this->category = $db_post['id_cat'];
        $this->author = $db_post['id_user'];
    }
    

    /* get posts information bloc of functions */
    public function getCategory()
    {
        
    }

    public function getImage()
    {
        return $this->image_top;
    }

    public function getContent()
    {
        
    }

    public function getState()
    {
        
    }

    public function getTitle()
    {
        
    }

    public function getAuthor()
    {
        
    }

    public function getDate_fr()
    {
        
    }
}
/* pour faire l'hydratation.
class Test
{
    protected $titre;
     
    public function __construct($valeurs = array())
    {
        if(!empty($valeurs))
            $this->hydrate($valeurs);
    }
 
    public function hydrate($donnees)
        {
            foreach ($donnees as $attribut => $valeur)
            {
            $methode = 'set'.str_replace(' ', '', ucwords(str_replace('_', ' ', $attribut)));
                 
            if (is_callable(array($this, $methode)))
            {
                $this->$methode($valeur);
            }
            }
        }
 
    public function setTitre($titre)
    {
        $this->titre = $titre;
    }
}