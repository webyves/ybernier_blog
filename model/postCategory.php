<?php
/***************************************************************** 
file: PostCategory.php 
Class model for post categories
******************************************************************/
namespace yBernier\Blog\Model;
require_once("model/Manager.php");
require_once("model/PostManager.php");

Class PostCategory extends PostManager 
{
    /* get list of post categories */
    public function getPostCategories()
    {
        // return table with categories
    }


    /* Set post categories information bloc of functions */
    public function setPostCategoryText($idCat, $text)
    {
        
    }
}