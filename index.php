<?php
/*****************************************************************
file: index.php
router and access point for website
******************************************************************/
use \yBernier\Blog\Autoloader;
use \yBernier\Blog\App;
use \yBernier\Blog\Router;

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
    $App->setErrorMessage($e->getMessage());
    $App->redirect('StaticPageController', 'errorPage');
}
