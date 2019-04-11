<?php

use Aptoma\Twig\Extension\MarkdownExtension;
use Aptoma\Twig\Extension\MarkdownEngine;

$container = $app->getContainer();

// Register component on container
$container['view'] = function ($container) {

    $dir = dirname(__DIR__);

    $engine = new MarkdownEngine\MichelfMarkdownEngine();

    $view = new \Slim\Views\Twig($dir . '/app/views', [
        'cache' => false//$dir . '/tmp'
    ]);

    $view->addExtension(new MarkdownExtension($engine));
    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

    return $view;
};

$container['notFoundHandler'] = function ($container) {
    return function ($request, $response) use ($container) {
        return $container->view->render($response, "404.twig");
    };
};

$container["view"]->getEnvironment()->addGlobal('session', $_SESSION);

