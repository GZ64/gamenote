<?php

session_start();

require('../library/loader/Autoloader.php');

$autoloader = \library\loader\AutoLoader::getInstance();
$autoloader::setBasePath(str_replace('public', '', __DIR__));

//    $test = new \application\controllers\Index();

\application\settings\Settings::getInstance();

$connexion = \library\core\Connexion::getInstance();
$connexion::setConnexion("localhost", $connexion::connectDB(
    DB_HOST,
    DB_NAME,
    DB_USER,
    DB_PASS,
    DB_CHAR
));


$router = \library\core\Router::getInstance();
$router::setProtectedRoutes(array(
    "jeu/create",
    "jeu/update/*",
    "jeu/delete/*"
));
$router::dispatchPage($_GET['p']);