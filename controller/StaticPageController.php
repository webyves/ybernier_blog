<?php
/*****************************************************************
file: StaticPageController.php
website Static Page Render Controller
******************************************************************/
namespace yBernier\Blog\controller;

use \yBernier\Blog\App;
use \yBernier\Blog\model\manager\Manager;

class StaticPageController extends PageController
{
    /***********************************
        Render Debug infos
    ***********************************/
    public function debugPage($varForDump, $varName = 'varName')
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
        $this->checkCaptchaV2($post);

        $tabInfo = array(
                        'fromFirstname' => $post['contactFirstname'],
                        'fromLastname' => $post['contactLastname'],
                        'fromEmail' => $post['contactEmail'],
                        'toEmail' => App::ADMIN_EMAIL,
                        'messageTxt' => $post['contactMessage'],
                        'messageHtml' => '',
                        'subject' => $post['contactSubject']
                    );
        $this->sendMail($tabInfo);
        
        echo $this->fTwig->render('frontoffice/contactConfirm.twig', array('postList' => $this->postList, 'postListMenu' => $this->postListMenu));
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
                $authRole = array(1,2);
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
