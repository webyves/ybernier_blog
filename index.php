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
    $router = new Router();
    $router->goRoad($App);
} catch (Exception $e) {
    $App = new App();
    $errorMessage = $e->getMessage();
    $controller = new StaticPageController($App);
    $controller->errorPage($errorMessage);
}
