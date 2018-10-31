<?php
/***************************************************************** 
file: StaticPageController.php 
website Static Page Render Controller
******************************************************************/
namespace yBernier\Blog\controller;

use \yBernier\Blog\model\manager\Manager;

Class StaticPageController extends PageController
{
    /*********************************** 
        Render Debug infos 
    ***********************************/
    public function debugPage($varForDump, $varName='varName')
    {
        echo $this->fTwig->render('debug.twig', array('varName' => $varName, 'forDump' => $varForDump));
    }

    /*********************************** 
        Render Error Page 
    ***********************************/
    public function errorPage($errorText)
    {
        echo $this->fTwig->render('error.twig', array('errorText' => $errorText, 'postListMenu' => $this->postListMenu));
    }

    /*********************************** 
        Generic Render Page 
    ***********************************/
    public function showPage($page = '')
    {
        if (!empty($page)) {
            echo $this->fTwig->render($page.'.twig', array('postListMenu' => $this->postListMenu));
        } else {
            throw new Exception('Page introuvable !');
        }
    }    
    
    /*********************************** 
        Function For Contact Form treatment 
            Make correct infos array
            Send to Manager for sending mail
            Render Confirmation Page
    ***********************************/
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