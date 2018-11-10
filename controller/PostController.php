<?php
/***************************************************************** 
file: PostController.php 
website Post controller
******************************************************************/
namespace yBernier\Blog\controller;

use \yBernier\Blog\model\manager\UserManager;
use \yBernier\Blog\model\manager\PostManager;
use \yBernier\Blog\model\manager\CommentManager;

Class PostController extends PageController
{
    /*********************************** 
        Render All posts  
    ***********************************/
    public function listPosts()
    {
        echo $this->fTwig->render('frontoffice/listPosts.twig', array('postList' => $this->postList, 'postListMenu' => $this->postListMenu));

    }

    /*********************************** 
        Render 1 specific post  
    ***********************************/
    public function post($idPost)
    {
        if (!is_numeric($idPost) || $idPost < 1 )
            throw new Exception('Post introuvable !');
        
        $postManager = new PostManager();
        $post = $postManager->getPost($idPost);
        
        $commentManager = new CommentManager();
        $nbcom = $commentManager->getCommentNb($idPost);
        $comments = $commentManager->getComments($idPost);

        echo $this->fTwig->render('frontoffice/postComments.twig', array('nbcom' => $nbcom, 'comments' => $comments, 'post' => $post, 'postListMenu' => $this->postListMenu));

    }
    
    /*********************************** 
        Function for Admin post categories List
    ***********************************/
    public function showAdminCatPostList($messageTwigView = "", $messageText = "") 
    {
        $authRole = array(1);
        $this->checkAccessByRole($_SESSION['userObject'], $authRole);

        $Manager = new PostManager();
        $catList = $Manager->getCats();
        
        echo $this->fTwig->render('backoffice/adminCatPosts'.$messageTwigView.'.twig', array('catList' => $catList, 'messageText' => $messageText));
    }
    
    /*********************************** 
        Function for Admin Category add form
    ***********************************/
    public function newCat($post) 
    {
        $authRole = array(1);
        $this->checkAccessByRole($_SESSION['userObject'], $authRole);
        
        if (empty(strip_tags($post['catAddModalText']))) {
            $this->showAdminCatPostList('Error', 'Le texte de la catégorie ne peu pas etre vide');
            
        } else {
            $tab = array (
                'text' => strip_tags($post['catAddModalText'])
                );
            $Manager = new PostManager();
            $Manager->addCat($tab);
            $this->showAdminCatPostList('Confirm');
        }
    }
    
    /*********************************** 
        Function for Admin Category modification form
    ***********************************/
    public function modifCat($post) 
    {
        $authRole = array(1);
        $this->checkAccessByRole($_SESSION['userObject'], $authRole);
        
        if (!is_numeric($post['catModifModalIdCat']) || $post['catModifModalIdCat'] == 1 ) {
            $this->showAdminCatPostList('Error', 'Modification Impossible sur cette catégorie');
            
        } elseif (empty(strip_tags($post['catModifModalText']))) {
            $this->showAdminCatPostList('Error', 'Le texte de la catégorie ne peu pas etre vide');
            
        } else {
            $tab = array (
                'idcat' => $post['catModifModalIdCat'],
                'text' => strip_tags($post['catModifModalText'])
                );
            $Manager = new PostManager();
            $Manager->updateCat($tab);
            $this->showAdminCatPostList('Confirm');
        }
    }
    
    /*********************************** 
        Function for Admin Category suppression form
    ***********************************/
    public function supCat($post) 
    {
        $authRole = array(1);
        $this->checkAccessByRole($_SESSION['userObject'], $authRole);
        
        if (!is_numeric($post['catSupModalIdCat']) || $post['catSupModalIdCat'] == 1 ) {
            $this->showAdminCatPostList('Error', 'Suppression Impossible sur cette catégorie');

        } else {
            $tab = array (
                'newcat' => 1,
                'oldcat' => $post['catSupModalIdCat'],
                'idpost' => 'all'
                );
            $Manager = new PostManager();
            $Manager->changePostCat($tab);
            $Manager->deleteCat($post['catSupModalIdCat']);
            $this->showAdminCatPostList('Confirm');
        }
    }
    
    /*********************************** 
        Function for Admin post 
    ***********************************/
    public function showAdminPostsPage($messageTwigView = "", $messageText = "") 
    {
        $authRole = array(1,2);
        $this->checkAccessByRole($_SESSION['userObject'], $authRole);

        $Manager = new PostManager();
        $postList = $Manager->getPosts('full_list', 'all', 'all');
        $catList = $Manager->getCats();
        $stateList = $Manager->getStates();
        
        echo $this->fTwig->render('backoffice/adminPosts'.$messageTwigView.'.twig', array('postList' => $postList, 'catList' => $catList, 'stateList' => $stateList, 'messageText' => $messageText));
    }
    
    /*********************************** 
        Function for Admin Edit post 
    ***********************************/
    public function showAdminEditPostPage($idPost, $messageTwigView = "", $messageText = "") 
    {
        $authRole = array(1,2);
        $this->checkAccessByRole($_SESSION['userObject'], $authRole);
        
        if (is_numeric($idPost) && $idPost > 0) {
            $Manager = new PostManager();
            $post = $Manager->getPost($idPost);
            $catList = $Manager->getCats();
            $stateList = $Manager->getStates();
            echo $this->fTwig->render('backoffice/adminEditPost'.$messageTwigView.'.twig', array('post' => $post, 'catList' => $catList, 'stateList' => $stateList, 'messageText' => $messageText));
        } else {
            throw new \Exception('Erreur sur le post');
        }
    }
    
    /*********************************** 
        Function for Admin Edit post 
    ***********************************/
    public function showAdminAddPostPage($messageTwigView = "", $messageText = "") 
    {
        $authRole = array(1,2);
        $this->checkAccessByRole($_SESSION['userObject'], $authRole);
        
        $Manager = new PostManager();
        $catList = $Manager->getCats();
        $stateList = $Manager->getStates();
        
        echo $this->fTwig->render('backoffice/adminAddPost'.$messageTwigView.'.twig', array('catList' => $catList, 'stateList' => $stateList, 'messageText' => $messageText));
    }
    
    /*********************************** 
        Function for Admin Fast Edit post 
    ***********************************/
    public function modifPost($post) 
    {
        $authRole = array(1,2);
        $this->checkAccessByRole($_SESSION['userObject'], $authRole);

        if (is_numeric($post['modifPostModalIdPost']) && $post['modifPostModalIdPost'] > 0) {
            $tab = array(
                'id_post' => (int)$post['modifPostModalIdPost'],
                'id_state' =>  (int)$post['modifPostModalSelEtat'],
                'id_cat' =>  (int)$post['modifPostModalSelCat']
                );
            $postManager = new PostManager();
            $oldPost = $postManager->getPost((int)$post['modifPostModalIdPost']);
            $postManager->updatePost($tab);
            
            if (isset($post['modifPostModalChkbxSendMail'])) {
                $updPost = $postManager->getPost((int)$post['modifPostModalIdPost']);
                $userManager = new UserManager();
                $author = $userManager->getUser($updPost->getIduser());
                
                $tabInfo = array( 
                        'fromFirstname' =>  "Administrateur",
                        'fromLastname' => "yBernier Blog",
                        'fromEmail' => $GLOBALS['adminEmail'],
                        'toEmail' => $author->getEmail(),
                        'messageTxt' => "Votre post anciennement intitulé : \n"
                                        .$oldPost->getTitle().
                                        "\n viens d'être mis à jour par : \n"
                                        .$_SESSION['userObject']->getFirstname()." ".$_SESSION['userObject']->getLastname(),
                        'messageHtml' => "Votre post anciennement intitulé : <br>
                                        <b>".$oldPost->getTitle()."</b><br>
                                        viens d'être mis à jour par : <br>
                                        <b>".$_SESSION['userObject']->getFirstname()." ".$_SESSION['userObject']->getLastname()."</b>",
                        'subject' => "[yBernier Blog] - Mise à jour de votre post."                         
                    );
                $this->sendMail($tabInfo);
            }
            
            $this->showAdminPostsPage('Confirm');
        } else {
            throw new \Exception('Erreur sur le post');
        }
    }
    
    /*********************************** 
        Function for Admin Full Edit post 
    ***********************************/
    public function editPost($post, $files, $idPost) 
    {
        $authRole = array(1,2);
        $this->checkAccessByRole($_SESSION['userObject'], $authRole);
        
        
        if (is_numeric($idPost) && $idPost > 0) {
            $tab = array(
                'id_post' => (int)$idPost,
                'title' => strip_tags($post['fullModifPostTitle']),
                'content' => $this->valideHtml($post['fullModifPostContent']),
                'id_state' => (int)$post['fullModifPostSelEtat'],
                'id_cat' => (int)$post['fullModifPostSelCat']
                );
                
            if ($files['fullModifPostImage']['size']>0) {
                $imageTop = $this->uploadImagePost($files['fullModifPostImage'], $idPost);
                $tab['image_top'] = $imageTop;
            }
            
            $postManager = new PostManager();
            $oldPost = $postManager->getPost((int)$idPost);
            $postManager->updatePost($tab);
            
            if (isset($post['fullModifPostChkbxSendMail'])) {
                $updPost = $postManager->getPost((int)$idPost);
                $userManager = new UserManager();
                $author = $userManager->getUser($updPost->getIduser());
                
                $tabInfo = array( 
                        'fromFirstname' =>  "Administrateur",
                        'fromLastname' => "yBernier Blog",
                        'fromEmail' => $GLOBALS['adminEmail'],
                        'toEmail' => $author->getEmail(),
                        'messageTxt' => "Votre post anciennement intitulé : \n"
                                        .$oldPost->getTitle().
                                        "\n viens d'être mis à jour par : \n"
                                        .$_SESSION['userObject']->getFirstname()." ".$_SESSION['userObject']->getLastname(),
                        'messageHtml' => "Votre post anciennement intitulé : <br>
                                        <b>".$oldPost->getTitle()."</b><br>
                                        viens d'être mis à jour par : <br>
                                        <b>".$_SESSION['userObject']->getFirstname()." ".$_SESSION['userObject']->getLastname()."</b>",
                        'subject' => "[yBernier Blog] - Mise à jour de votre post."                         
                    );
                $this-> sendMail($tabInfo);
            }
            
            $this->showAdminEditPostPage((int)$idPost,'Confirm');
        } else {
            throw new \Exception('Erreur sur le post');
        }
    }

    /*********************************** 
        Function for Admin Add new post 
    ***********************************/
    public function addPost($post, $files) 
    {
        $authRole = array(1,2);
        $this->checkAccessByRole($_SESSION['userObject'], $authRole);


        $tab = array(
            'title' => strip_tags($post['addPostTitle']),
            'content' =>  $this->valideHtml($post['addPostContent']),
            'id_state' => (int)$post['addPostSelEtat'],
            'id_cat' => (int)$post['addPostSelCat'],
            'image_top' => 'default.jpg',
            'id_user' => $_SESSION['userObject']->getIduser()
            );
        $postManager = new PostManager();
        $idNewPost = $postManager->addPost($tab);
        
        if ($files['addPostImage']['size'] > 0) {
            $imageTop = $this->uploadImagePost($files['addPostImage'], $idNewPost);
            $imageTab = array(
                'id_post' => $idNewPost,
                'image_top' => $imageTop
                );
            $postManager->updatePost($imageTab);
        }
        
        $this->showAdminEditPostPage($idNewPost,'ConfirmAdd');

    }
    
    /*********************************** 
        Function to check error on upload and sendback goog intel 
    ***********************************/
    public function uploadImagePost($files, $idPost) 
    {
        if ($files['error'] > 0)
            throw new \Exception('Erreur lors de l\'envoie du fichier');
        
        if ($files['size'] > $GLOBALS['maxFileSize']) 
            throw new \Exception('Le fichier a envoyer est trop Gros');
        
        $extensions_valides = array('jpg', 'jpeg', 'gif', 'png');
        $extension_upload = strtolower(substr(strrchr($files['name'], '.'), 1));
        if (!in_array($extension_upload,$extensions_valides) )
            throw new \Exception('Extension invalide pour le fichier');

        
        $nomFic = "imageTop".$idPost.".".$extension_upload;
        $nom = "public/img/post/".$nomFic;

        $resultat = move_uploaded_file($files['tmp_name'], $nom);
        if (!$resultat)
            throw new \Exception('Le transfert a achoué');
        
        return $nomFic;
        
    }

    /*********************************** 
        Function to check HTML text before send in DB
            suppr script balise with regex
    ***********************************/
    public function valideHtml($html) 
    {
        $result = preg_replace('#(<|&lt;)script(.*?)(>|&gt;)(.*?)(<|&lt;)/script(>|&gt;)#is', '', $html);
        return $result;
    }

    
}
