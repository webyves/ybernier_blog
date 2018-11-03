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
        echo $this->fTwig->render('frontoffice/debug.twig', array('varName' => $varName, 'forDump' => $varForDump));
    }

    /*********************************** 
        Render Error Page 
    ***********************************/
    public function errorPage($errorText)
    {
        echo $this->fTwig->render('frontoffice/error.twig', array('errorText' => $errorText, 'postListMenu' => $this->postListMenu));
    }

    /*********************************** 
        Generic Render Page 
    ***********************************/
    public function showPage($page = '')
    {
        if (!empty($page)) {
            echo $this->fTwig->render('frontoffice/'.$page.'.twig', array('postListMenu' => $this->postListMenu));
        } else {
            throw new \Exception('Page introuvable !');
        }
    }    
    
    /*********************************** 
        Function For Contact Form treatment 
            CHECK CAPTCHA
            Make correct infos array
            Send to Manager for sending mail
            Render Confirmation Page
    ***********************************/
    public function contact($post)
    {
        // check google reCAPTCHA V2
        $secret = "6LfhH2kUAAAAAKLIzyNxVbfvHVuTNZ7RU3EwYeXJ";
        $response = $_POST['g-recaptcha-response'];
        $remoteip = $_SERVER['REMOTE_ADDR'];
        $api_url = "https://www.google.com/recaptcha/api/siteverify?secret=" 
            . $secret
            . "&response=" . $response
            . "&remoteip=" . $remoteip ;
        $decode = json_decode(file_get_contents($api_url), true);
        
        if ($decode['success'] == true) {
            // it's Human
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
            
            echo $this->fTwig->render('frontoffice/contactConfirm.twig', array('postList' => $this->postList, 'postListMenu' => $this->postListMenu));
        } else {
            // it's Robot
            throw new \Exception('Erreur d\'identification');
        }
    }    
    
    /*********************************** 
        Generic Render Admin Page 
    ***********************************/
    public function showAdminPage($page = '')
    {
        switch ($page) {
            case 'admin':
            case 'adminAddPost':
            case 'adminPosts':
            case 'adminComments':
                $authRole = array(1,2);
                break;
            case 'adminCatPosts':
            case 'adminUsers':
                $authRole = array(1);
                break;
            default:
                $authRole = array(-1);
                break;
        }
        
        $this->checkAccessByRole($_SESSION['userObject'], $authRole);
        if (!empty($page)) {
            echo $this->fTwig->render('backoffice/'.$page.'.twig', array());
        } else {
            throw new \Exception('Page introuvable !');
        }
    }    
    
        
    
    
    
}