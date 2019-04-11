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

        if (isset($_SESSION["auth"])) {
            $post->authed = 1;
        }

        if (empty($post)) {
            return $this->render($response, "404.twig");
        }

        return $this->render($response, "Post.twig", compact("post"));
    }

    public function Posts($request, $response) {
        $request = self::getDB()->prepare("SELECT * FROM posts ORDER BY date DESC");
        $request->execute();
        $posts = $request->fetchAll();
        $this->render($response, "Posts.twig", compact("posts"));
    }

    public function PostsEditor($request, $response, $args) {
        if (!isset($args['slug'])) {
            return $this->render($response, "404.twig");
        }

        $request = self::getDB()->prepare("SELECT * FROM posts WHERE slug = ?");
        $request->execute([$args['slug']]);
        $post = $request->fetch();

        if (empty($post)) {
            return $this->render($response, "404.twig");
        }

        if (!isset($_SESSION["auth"])) {
            return $response->withRedirect('/');
        } else
            $post->authed = 1;



        return $this->render($response, "EditPost.twig", compact("post"));
    }

    public function DoPostsEditor($request, $response, $args) {
        if (!isset($args['slug'])) {
            return $this->render($response, "404.twig");
        }

        if (!isset($_SESSION["auth"])) {
            return $response->withRedirect('/');
        }

        $post = $request->getParsedBody();

        $r = self::getDB()->prepare("UPDATE posts SET title = ?, teaser = ?, trending = ?, content = ?, date = ? WHERE slug = ?");
        $r->execute([
            $post["title"],
            $post["teaser"],
            $post["trending"],
            $post["content"],
            time(),
            $args['slug']
        ]);

        return $response->withRedirect('/post/' . $args['slug']);
    }

}