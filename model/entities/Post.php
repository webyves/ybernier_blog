<?php
/***************************************************************** 
file: Post.php 
Class entities for post
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
    private $nbcom;
    
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
        $this->setSmallcontent($this->GetContent());

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

    public function getSmallcontent()
    {
        return $this->smallcontent;
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

    public function getNbcom()
    {
        return $this->nbcom;
    }

    /* Set posts information bloc of functions */
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
       $this->smallcontent = $this->texte_resume($value,100);
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

    public function setNbcom($value)
    {
       $this->nbcom = $value;
    }
    
    /*********************************** 
        Function to generate smallcontent
    ***********************************/
    private function texte_resume($texte, $nbreCar)
    {
        $LongueurTexteBrutSansHtml = strlen(strip_tags($texte));
        if($LongueurTexteBrutSansHtml < $nbreCar) 
            return $texte;

        $MasqueHtmlSplit = '#</?([a-zA-Z1-6]+)(?: +[a-zA-Z]+="[^"]*")*( ?/)?>#';
        $MasqueHtmlMatch = '#<(?:/([a-zA-Z1-6]+)|([a-zA-Z1-6]+)(?: +[a-zA-Z]+="[^"]*")*( ?/)?)>#';

        $texte .= ' ';
        $BoutsTexte = preg_split($MasqueHtmlSplit, $texte, -1,  PREG_SPLIT_OFFSET_CAPTURE | PREG_SPLIT_NO_EMPTY);
        $NombreBouts = count($BoutsTexte);

        if ($NombreBouts == 1) {
            $longueur = strlen($texte);
            $texte = substr($texte, 0, strpos($texte, ' ', $longueur > $nbreCar ? $nbreCar : $longueur));
            $texte = $texte . " [...]";
            return $texte;
        }

        $longueur = 0;
        $indexDernierBout = $NombreBouts - 1;
        $position = $BoutsTexte[$indexDernierBout][1] + strlen($BoutsTexte[$indexDernierBout][0]) - 1;
        $indexBout = $indexDernierBout;
        $rechercheEspace = true;

        foreach ($BoutsTexte as $index => $bout) {
            $longueur += strlen($bout[0]);
            if ($longueur >= $nbreCar) {
                $position_fin_bout = $bout[1] + strlen($bout[0]) - 1;
                $position = $position_fin_bout - ($longueur - $nbreCar);

                if (($positionEspace = strpos($bout[0], ' ', $position - $bout[1])) !== false) {
                    $position = $bout[1] + $positionEspace;
                    $rechercheEspace = false;
                }

                if ($index != $indexDernierBout)
                        $indexBout = $index + 1;
                break;
            }
        }

        if ($rechercheEspace === true) {
            for ($i=$indexBout; $i<=$indexDernierBout; $i++) {
                $position = $BoutsTexte[$i][1];
                if (($positionEspace = strpos($BoutsTexte[$i][0], ' ')) !== false ) {
                    $position += $positionEspace;
                    break;
                }
            }
        }

        $texte = substr($texte, 0, $position);
        preg_match_all($MasqueHtmlMatch, $texte, $retour, PREG_OFFSET_CAPTURE);
        $BoutsTag = array();

        foreach( $retour[0] as $index => $tag ) {
            if(isset($retour[3][$index][0])) {
                continue;
            }

            if( $retour[0][$index][0][1] != '/' ) {
                array_unshift($BoutsTag, $retour[2][$index][0]);
            } else {
                array_shift($BoutsTag);
            }
        }

        if( !empty($BoutsTag) ) {
            foreach( $BoutsTag as $tag ) {
                    $texte .= '</' . $tag . '>';
            }
        }

        if ($LongueurTexteBrutSansHtml > $nbreCar) {
            $texte .= ' [......]';
            $texte =  str_replace('</p> [......]', '... </p>', $texte);
            $texte =  str_replace('</ul> [......]', '... </ul>', $texte);
            $texte =  str_replace('</div> [......]', '... </div>', $texte);
        }

        $texte = $texte . " [...]";
        return $texte;
    }
    
}
