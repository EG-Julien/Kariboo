<?php

namespace App\Controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HomeCtrl extends Controller {
    public function Home($request, $response) {
        $this->render($response, "Home.twig");
    }

    public function About($request, $response) {
        $this->render($response, "About.twig");
    }

    public function Posts($request, $response) {
        $this->render($response, "Posts.twig");
    }
}