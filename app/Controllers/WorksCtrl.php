<?php

namespace App\Controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class WorksCtrl extends Controller {
    public function WorksViewer(RequestInterface $request, ResponseInterface $response) {
        $this->render($response, "Works.twig");
    }
}