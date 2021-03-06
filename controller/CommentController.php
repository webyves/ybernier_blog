<?php
/*****************************************************************
file: CommentController.php
website Comment controller
******************************************************************/
namespace yBernier\Blog\controller;

use \yBernier\Blog\App;
use \yBernier\Blog\model\manager\CommentManager;
use \yBernier\Blog\model\manager\PostManager;
use \yBernier\Blog\model\manager\UserManager;

class CommentController extends PostController
{
    
    /***********************************
        Function for Adding comment
            check if response comments or just new comments
            send correct infos to comment manager
            send email to administrator
    ***********************************/
    public function addComment()
    {
        $post = $this->fApp->getFPost();
        $idPost = $this->fApp->getFGetI();
        if (!is_numeric($idPost) || $idPost < 1) {
            throw new \Exception('Post introuvable !');
        }
        
        $postManager = new PostManager();
        $postObject = $postManager->getPost($idPost);
        if ($postObject->getIdstate() == 2) {
            throw new \Exception('Post Bloqué aucun commentaire possible !');
        }
        
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
            $commentManager->addComment($textCom, $this->fApp->getConnectedUser()->getIduser(), $idPost, $idComParent);
            $nbcom = $commentManager->getCommentNb($idPost);
            $comments = $commentManager->getComments($idPost);
            
            
            $tabInfo = array(
                    'fromFirstname' =>  "Administrateur",
                    'fromLastname' => "yBernier Blog",
                    'fromEmail' => APP::ADMIN_EMAIL,
                    'toEmail' => APP::ADMIN_EMAIL,
                    'messageTxt' => "Un nouveau commentaire viens d'etre ajouté pour le post ".$idPost.", merci de le lire avant de le valider.",
                    'messageHtml' => "",
                    'subject' => "[yBernier Blog] - Nouveau commentaire"
                );
            $this-> sendMail($tabInfo);
            
            $this->setPostListMenu();
            $this->setPostList();
            echo $this->fTwig->render('frontoffice/postCommentsConfirm.twig', array('nbcom' => $nbcom, 'comments' => $comments, 'post' => $postObject, 'postListMenu' => $this->postListMenu));
        } else {
            $this->showPost();
        }
    }
    
    /***********************************
        Function for Admin user List
    ***********************************/
    public function showAdminCommentList($messageTwigView = "")
    {
        $authRole = array(1,2);
        $this->checkAccessByRole($this->fApp->getConnectedUser(), $authRole);
        
        $Manager = new CommentManager();
        $CommentList = $Manager->getCommentList();
        $CommentStateList = $Manager->getStateList();
        $this->setPostList('by_id', 'all', 'all');
        
        echo $this->fTwig->render('backoffice/adminComments'.$messageTwigView.'.twig', array('comments' => $CommentList, 'CommentStateList' => $CommentStateList, 'postlist' => $this->postList));
    }

    /***********************************
        Function for update comment
    ***********************************/
    public function modifComment()
    {
        $post = $this->fApp->getFPost();
        $authRole = array(1,2);
        $this->checkAccessByRole($this->fApp->getConnectedUser(), $authRole);
        
        if (is_numeric($post['commentModalIdCom']) && $post['commentModalIdCom'] > 0) {
            if (is_numeric($post['commentModalSelEtat']) && $post['commentModalSelEtat'] > 0) {
                $comManager = new CommentManager();
                $tab = array(
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
                            'fromEmail' => APP::ADMIN_EMAIL,
                            'toEmail' =>  $user->getEmail(),
                            'messageTxt' => "L'etat de votre commentaire viens d'etre mis à jour.",
                            'messageHtml' => "",
                            'subject' => "[yBernier Blog] - Mise à jour de votre Commentaire"
                        );
                    $this-> sendMail($emailInfo);
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
