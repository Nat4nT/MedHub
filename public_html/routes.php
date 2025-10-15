<?php

use Slim\App;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

return function (App $app) {
    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write("Página inicial");
        return $response;
    });

    $app->get('/sobre', function (Request $request, Response $response) {
        $response->getBody()->write("Página Sobre");
        return $response;
    });

    $app->post('/enviar', function (Request $request, Response $response) {
        $data = $request->getParsedBody();
        $nome = $data['nome'] ?? 'Desconhecido';

        $response->getBody()->write("Olá, $nome!");
        return $response;
    });
};
