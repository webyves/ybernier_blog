<?php
/*****************************************************************
file: Router.php
Router of the website
******************************************************************/
namespace yBernier\Blog;

use \yBernier\Blog\App;

class Router
{
    // Define all website roads
    const ROADS_MAP = array(
        // Error Pages
        'erreur' => array('controller' => 'StaticPageController', 'method' => 'errorPage'),
        // FRONT OFFICE INFORMATIONS PAGES
        'mentions' => array('controller' => 'StaticPageController', 'method' => 'showPage'),
        'confidentialite' => array('controller' => 'StaticPageController', 'method' => 'showPage'),
        // FRONT OFFICE CONTACT
        'contact' => array('controller' => 'StaticPageController', 'method' => 'showPage'),
        'sendContactForm' => array('controller' => 'StaticPageController', 'method' => 'contact'),
        // FRONT OFFICE INSCRIPTION
        'inscription' => array('controller' => 'StaticPageController', 'method' => 'showPage'),
        'sendInscriptionForm' => array('controller' => 'StaticPageController', 'method' => 'inscription'),
        // FRONT OFFICE 1 POST PAGE
        'post' => array('controller' => 'PostController', 'method' => 'post'),
        // FRONT OFFICE SEND A COMMENT FORM RETURN
        'sendCommentForm' => array('controller' => 'CommentController', 'method' => 'addComment'),
        // BACK OFFICE HOME
        'admin' => array('controller' => 'StaticPageController', 'method' => 'showAdminPage'),
        // BACK OFFICE UPDATE POSTS
        'adminPosts' => array('controller' => 'PostController', 'method' => 'showAdminPostsPage'),
        'sendAdminPostModifForm' => array('controller' => 'PostController', 'method' => 'modifPost'),
        'adminEditPost' => array('controller' => 'PostController', 'method' => 'showAdminEditPostPage'),
        'sendAdminPostFullModifForm' => array('controller' => 'PostController', 'method' => 'editPost'),
        // BACK OFFICE NEW POSTS
        'adminAddPost' => array('controller' => 'PostController', 'method' => 'showAdminAddPostPage'),
        'sendAdminAddPostForm' => array('controller' => 'PostController', 'method' => 'addPost'),
        // BACK OFFICE CAT POSTS
        'adminCatPosts' => array('controller' => 'CatPostController', 'method' => 'showAdminCatPostList'),
        'sendAdminCatAddForm' => array('controller' => 'CatPostController', 'method' => 'newCat'),
        'sendAdminCatModifForm' => array('controller' => 'CatPostController', 'method' => 'modifCat'),
        'sendAdminCatSupForm' => array('controller' => 'CatPostController', 'method' => 'supCat'),
        // BACK OFFICE COMMENTS
        'adminComments' => array('controller' => 'CommentController', 'method' => 'showAdminCommentList'),
        'sendAdminCommentModifForm' => array('controller' => 'CommentController', 'method' => 'modifComment'),
        // BACK OFFICE USERS
        'adminUsers' => array('controller' => 'UserController', 'method' => 'showAdminUserList'),
        'sendAdminUserModifForm' => array('controller' => 'UserController', 'method' => 'modifUser')
    );
    
    public function goRoad(App $App)
    {
        if (isset(self::ROADS_MAP[$App->getFGetP()])) {
            $controller = '\yBernier\Blog\controller\\' . self::ROADS_MAP[$App->getFGetP()]['controller'];
            $method = self::ROADS_MAP[$App->getFGetP()]['method'];
            $road = new $controller($App);
            $road->$method();
        } else {
            if ($App->getFGetP() == 'logout') {
                // LOGOUT SPECIAL CASE
                $controller = new \yBernier\Blog\controller\UserController($App);
                $UserConnected = $controller->logout(true);
                $App->setConnectedUser($UserConnected);
            }
            $postController = new \yBernier\Blog\controller\PostController($App);
            $postController->listPosts();
        }
    }
}
