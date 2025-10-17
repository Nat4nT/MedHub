<?php

use App\Controller\LoginController;
use App\Controller\UsuarioController;
use App\Middlewares\AutenticacaoMiddleware;
use Slim\App;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Handlers\Strategies\RequestHandler;


return function (App $app) {

    $app->post('/login', [LoginController::class,'realizarLogin']);
    $app->post('/registrar', [UsuarioController::class,'realizarCadastro']);
    

    $app->get('/minha-conta/{/ref}',[UsuarioController::class,'pegarDadosConta'])->add(AutenticacaoMiddleware::class);

  
};
