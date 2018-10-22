<?php
/***************************************************************** 
file: PageController.php 
website Page Controller
******************************************************************/
namespace yBernier\Blog\controller;

use \yBernier\Blog\model\manager\PostManager;

Class PageController {
    
    protected $postList;
    protected $fTwig;
    
    public function __construct()
    {
        $this->setFTwig();
        $this->setPostList();
    }
    
    /* SET FUNCTION PARTS */
    public function setPostList()
    {
        $postManager = new PostManager();
        $listPosts = $postManager->getPosts();
        $this->postList = $listPosts;
    }
    
    public function setFTwig()
    {
        global $twig;
        $this->fTwig = $twig;
    }
    
    /* RENDER PAGE PARTS */
    public function debugPage($varName='varName', $varForDump)
    {
        echo $this->fTwig->render('debug.twig', array('varName' => $varName, 'forDump' => $varForDump));
    }

    public function errorPage($errorText)
    {
        echo $this->fTwig->render('error.twig', array('errorText' => $errorText, 'postListMenu' => $this->postList));
    }

    public function showPage($page = '')
    {
        if (!empty($page)) {
            echo $this->fTwig->render($page.'.twig', array('postListMenu' => $this->postList));
        } else {
            throw new Exception('Page introuvable !');
        }
    }    
    
}