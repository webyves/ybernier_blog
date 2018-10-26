<?php
/***************************************************************** 
file: PageController.php 
website Page Controller
******************************************************************/
namespace yBernier\Blog\controller;

use \yBernier\Blog\model\manager\PostManager;
use \yBernier\Blog\model\manager\Manager;

Class PageController {
    
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
    
    /* RENDER PAGE PARTS */
    public function debugPage($varForDump, $varName='varName')
    {
        echo $this->fTwig->render('debug.twig', array('varName' => $varName, 'forDump' => $varForDump));
    }

    public function errorPage($errorText)
    {
        echo $this->fTwig->render('error.twig', array('errorText' => $errorText, 'postListMenu' => $this->postListMenu));
    }

    public function showPage($page = '')
    {
        if (!empty($page)) {
            echo $this->fTwig->render($page.'.twig', array('postListMenu' => $this->postListMenu));
        } else {
            throw new Exception('Page introuvable !');
        }
    }    
    
    public function contact($post)
    {
        $tabInfo = array( 
                        'fromFirstname' => $post['contactFirstname'],
                        'fromLastname' => $post['contactLastname'],
                        'fromEmail' => $post['contactEmail'],
                        'toEmail' => "webyves@hotmail.com",             // Put your Administrator email
                        'messageTxt' => $post['contactMessage'],
                        'messageHtml' => '',                            // Empty from Contact form page
                        'subject' => $post['contactSubject']                        
                    );
        $Manager = new Manager();
        $Manager->sendMail($tabInfo);
        
        echo $this->fTwig->render('contactConfirm.twig', array('postList' => $this->postList, 'postListMenu' => $this->postListMenu));
    }    
    
    
    
}