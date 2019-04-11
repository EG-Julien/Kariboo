<?php

namespace App\Controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\UploadedFile;

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

    public function NewPost($request, $response) {

        if (!isset($_SESSION["auth"])) {
            return $response->withRedirect('/');
        }

        $this->render($response, "NewPost.twig");
    }

    public function SaveNewPost($request, $response) {
        if (!isset($_SESSION["auth"])) {
            return $response->withRedirect('/');
        }

        $post = $request->getParsedBody();

        $directory = __DIR__ . '/public/img/background';
        $uploadedFiles = $request->getUploadedFiles();

        $uploadedFile = $uploadedFiles['thumbmail'];

        var_dump($uploadedFiles);

        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
            $filename = $this->moveUploadedFile($directory, $uploadedFile);
        }

        $slug = $this->create_slug($post["title"]);

        $r = self::getDB()->prepare("INSERT INTO posts (title, slug, teaser, trending, author, date, content, thumbmail) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $r->execute([
            $post["title"],
            $slug,
            $post["teaser"],
            $post["trending"],
            $post["author"],
            time(),
            $post["content"],
            $filename
        ]);

        return $response->withRedirect('/post/' . $slug);
    }

    public function getContent($request, $response, $args) {
        if (!isset($args['slug'])) {
            return $this->render($response, "404.twig");
        }

        $request = self::getDB()->prepare("SELECT content FROM posts WHERE slug = ?");
        $request->execute([$args['slug']]);
        $post = $request->fetch();

        if (empty($post)) {
            return $this->render($response, "404.twig");
        }

        if (!isset($_SESSION["auth"])) {
            return $response->withRedirect('/');
        }

        return print_r($post->content);
    }

    private function create_slug($title) {
        $r = self::getDB()->prepare("SELECT id FROM posts ORDER BY id DESC LIMIT 1");
        $r->execute();
        $last_id = $r->fetch();

        $new_id = $last_id + 1;
        $title = str_replace(' ', '-', $title);

        return $title . '-' . $new_id;
    }

    private function str_random($length){
        $alphabet = "0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";
        return substr(str_shuffle(str_repeat($alphabet, $length)), 0, $length);
    }

    private function moveUploadedFile($directory, UploadedFile $uploadedFile)
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = $this->str_random(20) . "." . $extension;

        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $basename);

        return $basename;
    }

}