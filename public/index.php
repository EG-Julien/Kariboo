<?php
@session_start();

date_default_timezone_set('Europe/Paris');

require '../vendor/autoload.php';
require '../config.php';

$app = new \Slim\App(
    [
        'settings' => [
            'displayErrorDetails' => true
        ]
    ]
);

require '../app/container.php';

try {
    $DB = new PDO('mysql:dbname=' . $dbname . ';host=' . $dbhost . ';charset=utf8', "$dbuser", "$dbpassword");
    $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $DB->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
} catch (Exception $e) {
    die($e);
}

\App\Controllers\Controller::setDB($DB);

$app->get('/', \App\Controllers\HomeCtrl::class . ':Home')->setName("home");
$app->get('/about', \App\Controllers\HomeCtrl::class . ':About')->setName("about");
$app->get('/posts', \App\Controllers\HomeCtrl::class . ':Posts')->setName("posts");

$app->run();