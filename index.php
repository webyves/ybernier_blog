<?php
/*****************************************************************
file: index.php
router and access point for website
******************************************************************/
use \yBernier\Blog\Autoloader;
use \yBernier\Blog\App;
use \yBernier\Blog\Router;
use \yBernier\Blog\controller\StaticPageController;
use \yBernier\Blog\controller\UserController;

//Autoload
require_once('Autoloader.php');
Autoloader::register();
require_once('vendor/autoload.php');

//SESSION INIT
session_start();

try {
    $App = new App();
    $postData = $App->getFPost();
    $sessionData = $App->getFSession();
    // CONNEXION
    $UserController = new UserController($App);
    if (isset($sessionData['userObject']) && !is_null($sessionData['userObject']->getEmail())) {
        $UserConnected =  $sessionData['userObject'];
    } else {
        if (isset($postData['conexEmail']) && isset($postData['conexInputPassword'])) {
            $UserConnected = $UserController->connect($postData['conexEmail'], $postData['conexInputPassword']);
            if (isset($postData['conexChkbxRemember'])) {
                $UserController->generateUserCookie($UserConnected);
            }
        } else {
            $UserConnected = $UserController->getCookieInfo();
        }
    }
    $App->setConnectedUser($UserConnected);
    $router = new Router();
    $router->goRoad($App);
} catch (Exception $e) {
    $errorMessage = $e->getMessage();
    $controller = new StaticPageController($App);
    $controller->errorPage($errorMessage);
}
