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
    public function errorPage($errorText = '', $codeError = 0)
    {
        switch ($codeError) {
            case 400:
                $errorText = '<strong>Erreur 400:</strong> <em>Bad Request</em><br>La syntaxe de la requête est mal formulée.';
                break;
            case 401:
                $errorText = '<strong>Erreur 401:</strong> <em>Unhautorized</em><br>l\'utilisateur n\'a pas entré le bon mot de passe pour accéder au contenu.';
                break;
            case 403:
                $errorText = '<strong>Erreur 403:</strong> <em>Forbidden</em><br>l\'accès au contenu est interdit.';
                break;
            case 404:
                $errorText = '<strong>Erreur 404:</strong> <em>Not Found</em><br>le document n\'a pas été trouvé.';
                break;
            case 500:
                $errorText = '<strong>Erreur 403:</strong> <em>Internal Server Error</em><br> le serveur a rencontré une erreur interne.';
                break;
            case 503:
                $errorText = '<strong>Erreur 503:</strong> <em>Service Unvailable</em><br>le serveur ne peut pas répondre à cause d\'une surcharge de trafic.';
                break;
        }    
        
        echo $this->fTwig->render('frontoffice/error.twig', array('errorText' => $errorText, 'postListMenu' => $this->postListMenu));
    }

    /***********************************
        Generic Render Page
    ***********************************/
    public function showPage($page = '')
    {
        if (empty($page)) { 
            throw new \Exception('Page introuvable !');
        }
        echo $this->fTwig->render('frontoffice/'.$page.'.twig', array('postListMenu' => $this->postListMenu));
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
                $authRole = array(1,2);
                break;
            default:
                $authRole = array(-1);
                break;
        }
        
        $this->checkAccessByRole($_SESSION['userObject'], $authRole);
        
        if (empty($page)) {
            throw new \Exception('Page introuvable !');
        }
        echo $this->fTwig->render('backoffice/'.$page.'.twig', array());
    }
}
