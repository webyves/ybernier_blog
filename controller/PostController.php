<?php
/*****************************************************************
file: PostController.php
website Post controller
******************************************************************/
namespace yBernier\Blog\controller;

use \yBernier\Blog\App;
use \yBernier\Blog\model\manager\PostManager;
use \yBernier\Blog\model\manager\CatPostManager;
use \yBernier\Blog\model\manager\CommentManager;
use \yBernier\Blog\model\manager\UserManager;
use \yBernier\Blog\model\entities\Post;

class PostController extends PageController
{
    /***********************************
        Render All posts
    ***********************************/
    public function listPosts()
    {
        $this->setPostListMenu();
        $this->setPostList();
        echo $this->fTwig->render('frontoffice/listPosts.twig', array('postList' => $this->postList, 'postListMenu' => $this->postListMenu));
    }

    /***********************************
        Render 1 specific post
    ***********************************/
    public function post($idPost)
    {
        if (!is_numeric($idPost) || $idPost < 1) {
            throw new \Exception('Post introuvable !');
        }
        
        $postManager = new PostManager();
        $post = $postManager->getPost($idPost);
        
        $commentManager = new CommentManager();
        $nbcom = $commentManager->getCommentNb($idPost);
        $comments = $commentManager->getComments($idPost);

        $this->setPostListMenu();
        $this->setPostList();
        echo $this->fTwig->render('frontoffice/postComments.twig', array('nbcom' => $nbcom, 'comments' => $comments, 'post' => $post, 'postListMenu' => $this->postListMenu));
    }
    
    
    /***********************************
        Function for Admin post
    ***********************************/
    public function showAdminPostsPage($messageTwigView = "", $messageText = "")
    {
        $authRole = array(1,2);
        $this->checkAccessByRole($this->fApp->getConnectedUser(), $authRole);

        $PostManager = new PostManager();
        $postList = $PostManager->getPosts('full_list', 'all', 'all');
        $stateList = $PostManager->getStates();
        
        $CatPostManager = new CatPostManager();
        $catList = $CatPostManager->getCats();
        
        echo $this->fTwig->render('backoffice/adminPosts'.$messageTwigView.'.twig', array('postList' => $postList, 'catList' => $catList, 'stateList' => $stateList, 'messageText' => $messageText));
    }
    
    /***********************************
        Function for Admin Edit post
    ***********************************/
    public function showAdminEditPostPage($idPost, $messageTwigView = "", $messageText = "")
    {
        $authRole = array(1,2);
        $this->checkAccessByRole($this->fApp->getConnectedUser(), $authRole);
        
        if (is_numeric($idPost) && $idPost > 0) {
            $CatPostManager = new CatPostManager();
            $catList = $CatPostManager->getCats();
        
            $PostManager = new PostManager();
            $post = $PostManager->getPost($idPost);
            $stateList = $PostManager->getStates();
            
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
        $this->checkAccessByRole($this->fApp->getConnectedUser(), $authRole);
        
        $PostManager = new PostManager();
        $stateList = $PostManager->getStates();
        
        $CatPostManager = new CatPostManager();
        $catList = $CatPostManager->getCats();
        
        echo $this->fTwig->render('backoffice/adminAddPost'.$messageTwigView.'.twig', array('catList' => $catList, 'stateList' => $stateList, 'messageText' => $messageText));
    }
    
    /***********************************
        Function for Admin Fast Edit post
    ***********************************/
    public function modifPost($post)
    {
        $authRole = array(1,2);
        $this->checkAccessByRole($this->fApp->getConnectedUser(), $authRole);

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
                $this->sendMailModifPost($oldPost, $updPost);
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
        $this->checkAccessByRole($this->fApp->getConnectedUser(), $authRole);
        
        if (is_numeric($idPost) && $idPost > 0) {
            $postManager = new PostManager();
            $oldPost = $postManager->getPost((int)$idPost);
            
            $tab = array(
                'id_post' => (int)$idPost,
                'title' => strip_tags($post['fullModifPostTitle']),
                'content' => $this->valideHtml($post['fullModifPostContent']),
                'id_state' => (int)$post['fullModifPostSelEtat'],
                'id_cat' => (int)$post['fullModifPostSelCat']
                );
                
            if ($files['fullModifPostImage']['size']>0) {
                $imageTop = $this->uploadImagePost($files['fullModifPostImage'], $idPost, $oldPost->getImagetop());
                $tab['image_top'] = $imageTop;
            }
            
            $postManager->updatePost($tab);
            
            if (isset($post['fullModifPostChkbxSendMail'])) {
                $updPost = $postManager->getPost((int)$idPost);
                $this->sendMailModifPost($oldPost, $updPost);
            }
            
            $this->showAdminEditPostPage((int)$idPost, 'Confirm');
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
        $this->checkAccessByRole($this->fApp->getConnectedUser(), $authRole);


        $tab = array(
            'title' => strip_tags($post['addPostTitle']),
            'content' =>  $this->valideHtml($post['addPostContent']),
            'id_state' => (int)$post['addPostSelEtat'],
            'id_cat' => (int)$post['addPostSelCat'],
            'image_top' => 'default.jpg',
            'id_user' => $this->fApp->getConnectedUser()->getIduser()
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
        
        $this->showAdminEditPostPage($idNewPost, 'ConfirmAdd');
    }

    /***********************************
        Function for sending preprogrammed email for update post
    ***********************************/
    protected function sendMailModifPost(Post $oldPost, Post $updPost)
    {
        $userManager = new UserManager();
        $author = $userManager->getUser($updPost->getIduser());
        
        $tabInfo = array(
                'fromFirstname' =>  "Administrateur",
                'fromLastname' => "yBernier Blog",
                'fromEmail' => App::ADMIN_EMAIL,
                'toEmail' => $author->getEmail(),
                'messageTxt' => "Votre post anciennement intitulé : \n"
                                .$oldPost->getTitle().
                                "\n viens d'être mis à jour par : \n"
                                .$this->fApp->getConnectedUser()->getFirstname()." ".$this->fApp->getConnectedUser()->getLastname(),
                'messageHtml' => "Votre post que vous aviez intitulé : <br>
                                <b>".$oldPost->getTitle()."</b><br>
                                viens d'être mis à jour par: <br>
                                <b>".$this->fApp->getConnectedUser()->getFirstname()." ".$this->fApp->getConnectedUser()->getLastname()."</b>",
                'subject' => "[yBernier Blog] - Mise à jour de votre post."
            );

        $this-> sendMail($tabInfo);
    }    
    
    /***********************************
        Function to check error on upload and sendback good infos
    ***********************************/
    protected function uploadImagePost($files, $idPost, $oldImagetop = "default.jpg")
    {
        if ($files['error'] > 0) {
            throw new \Exception('Erreur lors de l\'envoie du fichier');
        }
        
        if ($files['size'] > APP::MAX_FILE_SIZE) {
            throw new \Exception('Le fichier a envoyer est trop Gros');
        }
        
        $extensions_valides = array('jpg', 'jpeg', 'gif', 'png');
        $extension_upload = strtolower(substr(strrchr($files['name'], '.'), 1));
        if (!in_array($extension_upload, $extensions_valides)) {
            throw new \Exception('Extension invalide pour le fichier');
        }
        
        $nomFic = "imageTop".$idPost.".".$extension_upload;
        $nom = "public/img/post/".$nomFic;
        
        if ($oldImagetop != "default.jpg" && $oldImagetop != $nomFic) {
            if (file_exists("public/img/post/" . $oldImagetop)) {
                unlink("public/img/post/" . $oldImagetop);
            }
        }

        $resultat = move_uploaded_file($files['tmp_name'], $nom);
        if (!$resultat) {
            throw new \Exception('Le transfert a achoué');
        }
        return $nomFic;
    }

    /***********************************
        Function to check HTML text before send in DB
            suppr script balise with regex
    ***********************************/
    protected function valideHtml($html)
    {
        $result = preg_replace('#(<|&lt;)script(.*?)(>|&gt;)(.*?)(<|&lt;)/script(>|&gt;)#is', '', $html);
        return $result;
    }
}
