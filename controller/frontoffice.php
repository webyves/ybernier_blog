<?php
/***************************************************************** 
file: frontoffice.php 
website frontoffice controler
******************************************************************/

//TWIG
require_once ('vendor/autoload.php');

$loader = new Twig_Loader_Filesystem('view/frontoffice');
$twig = new Twig_Environment($loader, array(
    'cache' => false, // 'view/frontend/cache',
    'debug' => true,
));
$twig->addExtension(new Twig_Extension_Debug());

//
require ('model/manager/Manager.php');

function listPosts()
{
    global $twig;
    require ('model/manager/PostManager.php');
    $postManager = new \yBernier\Blog\model\manager\PostManager();
    $postList = $postManager->getPosts();
    // var_dump ($postList);
    echo $twig->render('listPosts.twig', array('postList' => $postList));
    // echo $twig->render('index.html', array('name' => 'Fabien'));
    // require ('view/frontend/listPosts.php');
}

function post($idPost)
{
    // require ('model/Post.php');
    //on recupere dans la base de donnée via les function du modele et on appel la vue
    // require ('view/frontend/post.php');
}
