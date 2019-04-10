<?php

namespace App\Controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HomeCtrl extends Controller {
    public function Home($request, $response) {

        $request = self::getDB()->prepare("SELECT * FROM posts ORDER BY date DESC LIMIT 7");
        $request->execute();
        $posts = $request->fetchAll();

        $this->render($response, "Home.twig", compact("posts"));
    }

    public function About($request, $response) {
        $this->render($response, "About.twig");
    }
}