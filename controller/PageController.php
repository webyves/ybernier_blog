<?php
/***************************************************************** 
file: PageController.php 
Mother Class for Controller
******************************************************************/
namespace yBernier\Blog\controller;

use \yBernier\Blog\model\manager\PostManager;

Class PageController 
{
    
    protected $postList;
    protected $postListMenu;
    protected $fTwig;
    
    public function __construct()
    {
        $this->setFTwig();
        $this->setPostList();
        $this->setPostListMenu();
    }
    
    /* SET FUNCTION PARTS */
    public function setPostListMenu()
    {
        $postManager = new PostManager();
        $this->postListMenu = $postManager->getPosts();
    }
    
    public function setPostList()
    {
        $postManager = new PostManager();
        $this->postList = $postManager->getPosts('full_list');
    }
    
    public function setFTwig()
    {
        global $twig;
        $this->fTwig = $twig;
    }
    
}