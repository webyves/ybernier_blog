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
        $this->postList = $postManager->getPosts();
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
        
        $postManager = new PostManager();
        $postList = $postManager->getPosts('full_list');
        echo $this->fTwig->render('confirmContact.twig', array('postList' => $postList, 'postListMenu' => $this->postList));
    }    
    
    
    
}