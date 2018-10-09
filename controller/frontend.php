<?php
/***************************************************************** 
file: frontend.php 
website frontend controler
******************************************************************/

// require ('model.php');

function listPosts()
{
    //on recupere dans la base de donnée via les function du modele et on appel la vue
    require ('view/frontend/listPosts.php');
}

function post($idPost)
{
    //on recupere dans la base de donnée via les function du modele et on appel la vue
    require ('view/frontend/post.php');
}
