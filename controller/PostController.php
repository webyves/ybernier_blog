<?php
/***************************************************************** 
file: PostController.php 
website Post controller
******************************************************************/
namespace yBernier\Blog\controller;

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
    
    
    
}