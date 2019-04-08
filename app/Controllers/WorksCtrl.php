<?php

namespace App\Controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class WorksCtrl extends Controller {

    public function PostsRender(RequestInterface $request, ResponseInterface $response, $args) {

        if (!isset($args['id'])) {
            $this->render($response, "404.twig");
        }

        $this->render($response, "Post.twig");
    }

}