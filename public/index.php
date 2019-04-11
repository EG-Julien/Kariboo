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
    $DB = new PDO('mysql:dbname=' . $dbname . ';host=' . $dbhost . ';port=3306;charset=utf8', "$dbuser", "$dbpassword");
    $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $DB->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
} catch (Exception $e) {
    die($e);
}

\App\Controllers\Controller::setDB($DB);

$app->get('/', \App\Controllers\HomeCtrl::class . ':Home')->setName("home");
$app->get('/about', \App\Controllers\HomeCtrl::class . ':About')->setName("about");
$app->get('/posts', \App\Controllers\WorksCtrl::class . ':Posts')->setName("posts");
$app->get('/post/{slug}', \App\Controllers\WorksCtrl::class . ':PostsRender')->setName("post_render");
$app->get('/edit/post/{slug}', \App\Controllers\WorksCtrl::class . ':PostsEditor')->setName("post_edit");
$app->post('/edit/post/{slug}', \App\Controllers\WorksCtrl::class . ':DoPostsEditor');

$app->get("/post/get/content/{slug}", \App\Controllers\WorksCtrl::class . ':getContent');

$app->get('/new/post/', \App\Controllers\WorksCtrl::class . ':NewPost')->setName("new");
$app->post('/new/post/', \App\Controllers\WorksCtrl::class . ':SaveNewPost');

$app->get('/login', \App\Controllers\LoginCtrl::class . ':Login')->setName("login");
$app->post('/login', \App\Controllers\LoginCtrl::class . ':getAuth');

$app->run();