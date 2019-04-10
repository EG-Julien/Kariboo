<?php

namespace App\Controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class WorksCtrl extends Controller {

    public function PostsRender(RequestInterface $request, ResponseInterface $response, $args) {

        if (!isset($args['slug'])) {
            return $this->render($response, "404.twig");
        }

        $request = self::getDB()->prepare("SELECT * FROM posts WHERE slug = ?");
        $request->execute([$args['slug']]);
        $post = $request->fetch();

        return $this->render($response, "Post.twig", compact("post"));
    }

    public function Posts($request, $response) {
        $request = self::getDB()->prepare("SELECT * FROM posts ORDER BY date DESC");
        $request->execute();
        $posts = $request->fetchAll();
        $this->render($response, "Posts.twig", compact("posts"));
    }

}