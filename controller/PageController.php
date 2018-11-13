<?php
/*****************************************************************
file: PageController.php
Mother Class for Controller
******************************************************************/
namespace yBernier\Blog\controller;

use \yBernier\Blog\App;
use \yBernier\Blog\model\manager\PostManager;
use \yBernier\Blog\model\entities\User;

class PageController
{
    protected $postList;
    protected $postListMenu;
    protected $fTwig;
    
    public function __construct(\Twig_Environment $twig)
    {
        $this->setFTwig($twig);
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
    
    public function setFTwig(\Twig_Environment $twig)
    {
        $this->fTwig = $twig;
    }
    
    
    /***********************************
        Function to check access to page
            check if User Role is admin or redacteur by default
    ***********************************/
    public function checkAccessByRole(User $objUser, $idRole = array(1,2))
    {
        if (!in_array($objUser->getIdrole(), $idRole)) {
            throw new \Exception('Utilisateur non autorisé !');
        }
    }


    /***********************************
        Function to check reCaptcha v2
    ***********************************/
    public function checkCaptchaV2($post)
    {
        if (!isset($post['g-recaptcha-response'])) {
            throw new \Exception('Erreur d\'envoie de donnée');
        }
        
        $secret = APP::CAPTCHA_SECRET_KEY;
        $response = $post['g-recaptcha-response'];
        $remoteip = $_SERVER['REMOTE_ADDR'];
        $api_url = "https://www.google.com/recaptcha/api/siteverify?secret="
            . $secret
            . "&response=" . $response
            . "&remoteip=" . $remoteip ;
        $decode = json_decode(file_get_contents($api_url), true);
        
        if ($decode['success'] !== true) {
            throw new \Exception('Erreur d\'identification');
        }
    }

    /***********************************
        Function for Sending eMail
        $tabInfo = array(
                'fromFirstname' => "",  // empty is OK
                'fromLastname' => "",   // empty is OK
                'fromEmail' => "",
                'toEmail' => "",
                'messageTxt' => "",
                'messageHtml' => "",    // empty is OK
                'subject' => ""         // empty is OK
            );
    ***********************************/
    public function sendMail($tabInfo)
    {
        $fromName = "Anonyme";
        if (!empty($tabInfo['fromFirstname']) || !empty($tabInfo['fromLastname'])) {
            $fromName = htmlentities($tabInfo['fromFirstname']) . " " . htmlentities($tabInfo['fromLastname']);
        }
        $fromEmail = $tabInfo['fromEmail'];
        $destEmail = htmlentities($tabInfo['toEmail']);
        $messageTxt = nl2br(htmlentities($tabInfo['messageTxt']));
        if (empty($tabInfo['messageHtml'])) {
            $messageHtml = $messageTxt;
        } else {
            $messageHtml = nl2br($tabInfo['messageHtml']); // ATTENTION SECURITY
        }
        if (empty($tabInfo['subject'])) {
            $subject = "Message depuis yBernier Blog";
        } else {
            $subject = $tabInfo['subject']; // ATTENTION SECURITY
        }
        $boundary = "-----=".md5(rand());
        
        if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $destEmail)) {
            $ligneStop = "\r\n";
        } else {
            $ligneStop = "\n";
        }

        $header = "From: \"$fromName\"<$fromEmail>".$ligneStop;
        $header .= "Reply-to: \"$fromName\" <$fromEmail>".$ligneStop;
        $header .= "MIME-Version: 1.0".$ligneStop;
        $header .= "Content-Type: multipart/alternative;".$ligneStop." boundary=\"$boundary\"".$ligneStop;

        $message = $ligneStop."--".$boundary.$ligneStop;
        $message.= "Content-Type: text/plain; charset=\"utf-8\"".$ligneStop;
        $message.= "Content-Transfer-Encoding: 8bit".$ligneStop;
        $message.= $ligneStop.$messageTxt.$ligneStop;
        $message.= $ligneStop."--".$boundary.$ligneStop;
        $message .= "Content-Type: text/html; charset=\"utf-8\"".$ligneStop;
        $message .= "Content-Transfer-Encoding: 8bit".$ligneStop;
        $message.= $ligneStop.$messageHtml.$ligneStop;
        $message.= $ligneStop."--".$boundary."--".$ligneStop;
        $message.= $ligneStop."--".$boundary."--".$ligneStop;

        mail($destEmail, $subject, $message, $header);
    }
}
