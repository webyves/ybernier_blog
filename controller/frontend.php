<?php
/***************************************************************** 
file: frontend.php 
website frontend controler
******************************************************************/

require ('model/Manager.php');
// require ('model/User.php');
// require ('model/Comment.php');

function listPosts()
{
    require ('model/PostManager.php');
    $postManager = new \yBernier\Blog\Model\PostManager();
    $postList = $postManager->getPosts();
    
    require ('view/frontend/listPosts.php');
}

function post($idPost)
{
    // require ('model/Post.php');
    //on recupere dans la base de donnée via les function du modele et on appel la vue
    // require ('view/frontend/post.php');
}
