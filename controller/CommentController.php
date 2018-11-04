<?php
/***************************************************************** 
file: CommentController.php 
website Comment controller
******************************************************************/
namespace yBernier\Blog\controller;

use \yBernier\Blog\model\manager\CommentManager;
use \yBernier\Blog\model\manager\PostManager;
use \yBernier\Blog\model\manager\UserManager;

Class CommentController extends PostController
{
    
    /*********************************** 
        Function for Adding comment 
            check if response comments or just new comments
            send correct infos to comment manager
            send email to administrator
    ***********************************/
    public function addComment($post, $UserConnected, $idPost) 
    {
        $idComParent = null;
        $textCom = "";
        
        if (isset($post['respComInputIdCom'])) {
            if (is_numeric($post['respComInputIdCom']) && $post['respComInputIdCom'] > 0) {
                $textCom = $post['respComInputText'];
                $idComParent = $post['respComInputIdCom'];
            } else {
                throw new \Exception('Commentaire invalide !');
            }
        } elseif (isset($post['comInputText'])) {
            $textCom = $post['comInputText'];
        }
        
        if (!empty($textCom)) {
            $commentManager = new CommentManager();
            $commentManager->addComment($textCom, $UserConnected->getIduser(), $idPost, $idComParent);
            $nbcom = $commentManager->getCommentNb($idPost);
            $comments = $commentManager->getComments($idPost);
            
            $postManager = new PostManager();
            $post = $postManager->getPost($idPost);
            
            $tabInfo = array( 
                    'fromFirstname' =>  "Administrateur",
                    'fromLastname' => "yBernier Blog",
                    'fromEmail' => $GLOBALS['adminEmail'],
                    'toEmail' => $GLOBALS['adminEmail'],
                    'messageTxt' => "Un nouveau commentaire viens d'etre ajouté pour le post ".$idPost.", merci de le lire avant de le valider.",
                    'messageHtml' => "",
                    'subject' => "[yBernier Blog] - Nouveau commentaire"                         
                );
            $commentManager-> sendMail($tabInfo);
            
            
            echo $this->fTwig->render('frontoffice/postCommentsConfirm.twig', array('nbcom' => $nbcom, 'comments' => $comments, 'post' => $post, 'postListMenu' => $this->postListMenu));
        } else {
            $this->post($idPost);
        }
    }
    
    /*********************************** 
        Function for Admin user List
    ***********************************/
    public function showAdminCommentList($messageTwigView = "") 
    {
        $authRole = array(1,2);
        $this->checkAccessByRole($_SESSION['userObject'], $authRole);
        
        $Manager = new CommentManager();
        $CommentList = $Manager->getCommentList();
        $CommentStateList = $Manager->getStateList();
        
        // $controller = new StaticPageController();
        // $controller->debugPage($CommentList);
        
        echo $this->fTwig->render('backoffice/adminComments'.$messageTwigView.'.twig', array('comments' => $CommentList, 'CommentStateList' => $CommentStateList));

    }    
    /*********************************** 
        Function for update comment
    ***********************************/
    public function modifComment($post) 
    {
        $authRole = array(1,2);
        $this->checkAccessByRole($_SESSION['userObject'], $authRole);
        
        if (is_numeric($post['commentModalIdCom']) && $post['commentModalIdCom'] > 0) {
            if (is_numeric($post['commentModalSelEtat']) && $post['commentModalSelEtat'] > 0) {
                $comManager = new CommentManager();
                $tab = array (
                    'idcom' => $post['commentModalIdCom'],
                    'idstate' => $post['commentModalSelEtat']
                    );
                $comManager->updateComment($tab);
                
                if (isset($post['commentModalChkbxSendMail'])) {
                    $userManager = new UserManager();
                    $user = $userManager->getUser($post['commentModalIdUser']);
                    $emailInfo = array( 
                            'fromFirstname' => "Administrateur",
                            'fromLastname' => "yBernier Blog",
                            'fromEmail' => $GLOBALS['adminEmail'],
                            'toEmail' =>  $user->getEmail(), 
                            'messageTxt' => "L'etat de votre commentaire viens d'etre mis à jour.",
                            'messageHtml' => "",
                            'subject' => "[yBernier Blog] - Mise à jour de votre Commentaire"                         
                        );
                    $userManager-> sendMail($emailInfo);
                }
                
                $this->showAdminCommentList('Confirm');
                
            } else {
                throw new \Exception('Valeur Incorrecte !');
            }
        } else {
            throw new \Exception('Commentaire Incorrect !');
        }
        
        
        
    }
    
}