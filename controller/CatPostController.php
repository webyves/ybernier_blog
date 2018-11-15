<?php
/*****************************************************************
file: CatPostController.php
website Categories posts controller
******************************************************************/
namespace yBernier\Blog\controller;

use \yBernier\Blog\model\manager\CatPostManager;
use \yBernier\Blog\model\manager\PostManager;

class CatPostController extends PostController
{
    /***********************************
        Function for Admin post categories List
    ***********************************/
    public function showAdminCatPostList($messageTwigView = "", $messageText = "")
    {
        $authRole = array(1);
        $this->checkAccessByRole($this->fApp->getConnectedUser(), $authRole);

        $Manager = new CatPostManager();
        $catList = $Manager->getCats();
        
        echo $this->fTwig->render('backoffice/adminCatPosts'.$messageTwigView.'.twig', array('catList' => $catList, 'messageText' => $messageText));
    }
    
    /***********************************
        Function for Admin Category add form
    ***********************************/
    public function newCat()
    {
        $post = $this->fApp->getFPost();
        $authRole = array(1);
        $this->checkAccessByRole($this->fApp->getConnectedUser(), $authRole);
        
        if (empty(strip_tags($post['catAddModalText']))) {
            $this->showAdminCatPostList('Error', 'Le texte de la catégorie ne peu pas etre vide');
        } else {
            $tab = array (
                'text' => strip_tags($post['catAddModalText'])
                );
            $Manager = new CatPostManager();
            $Manager->addCat($tab);
            $this->showAdminCatPostList('Confirm');
        }
    }
    
    /***********************************
        Function for Admin Category modification form
    ***********************************/
    public function modifCat()
    {
        $post = $this->fApp->getFPost();
        $authRole = array(1);
        $this->checkAccessByRole($this->fApp->getConnectedUser(), $authRole);
        
        if (!is_numeric($post['catModifModalIdCat']) || $post['catModifModalIdCat'] == 1) {
            $this->showAdminCatPostList('Error', 'Modification Impossible sur cette catégorie');
        } elseif (empty(strip_tags($post['catModifModalText']))) {
            $this->showAdminCatPostList('Error', 'Le texte de la catégorie ne peu pas etre vide');
        } else {
            $tab = array (
                'idcat' => $post['catModifModalIdCat'],
                'text' => strip_tags($post['catModifModalText'])
                );
            $Manager = new CatPostManager();
            $Manager->updateCat($tab);
            $this->showAdminCatPostList('Confirm');
        }
    }
    
    /***********************************
        Function for Admin Category suppression form
    ***********************************/
    public function supCat()
    {
        $post = $this->fApp->getFPost();
        $authRole = array(1);
        $this->checkAccessByRole($this->fApp->getConnectedUser(), $authRole);
        
        if (!is_numeric($post['catSupModalIdCat']) || $post['catSupModalIdCat'] == 1) {
            $this->showAdminCatPostList('Error', 'Suppression Impossible sur cette catégorie');
        } else {
            $tab = array (
                'newcat' => 1,
                'oldcat' => $post['catSupModalIdCat'],
                'idpost' => 'all'
                );
            $PostManager = new PostManager();
            $PostManager->changePostCat($tab);
            
            $CatPostManager = new CatPostManager();
            $CatPostManager->deleteCat($post['catSupModalIdCat']);
            
            $this->showAdminCatPostList('Confirm');
        }
    }
}
