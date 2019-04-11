<?php

namespace App\Controllers;

use http\Env\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class LoginCtrl extends Controller {
    public function Login(RequestInterface $request, ResponseInterface $response) {
        return $this->render($response, "Login.twig");
    }

    public function getAuth(RequestInterface $request, ResponseInterface $response) {

        $args = $request->getParsedBody();

        $r = self::getDB()->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
        $r->execute([
            $args["username"],
            sha1($args["password"])
        ]);

        $user = $r->fetch();

        if (empty($user)) {
            return $response->withRedirect('/404');
        }

        $_SESSION["auth"] = $user;

        return $response->withRedirect('/');
    }
}