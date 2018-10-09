<?php
/***************************************************************** 
file: frontend.php 
website frontend controler
******************************************************************/

// require ('model.php');
require ('model/db.php');
// require ('model/user.php');
// require ('model/comment.php');

function listPosts()
{
    require ('model/post.php');
    $postList = getPosts();
    
    require ('view/frontend/listPosts.php');
}

function post($idPost)
{
    require ('model/post.php');
    //on recupere dans la base de donnée via les function du modele et on appel la vue
    require ('view/frontend/post.php');
}
