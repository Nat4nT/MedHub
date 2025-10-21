<?php

use App\Controllers\LoginController;
use App\Controllers\UsuarioController;
use App\Middlewares\AutenticacaoMiddleware;
use Slim\App;


// caminho :C:\Users\Natan\Documents\Natan\TADS\Codigos\MedHUBApi\public_html\app\routes.php

return function (App $app) {

    $app->post('/login', [LoginController::class, 'realizarLogin']);

    $app->post('/registrar', [UsuarioController::class, 'realizarCadastro']);


    $app->get('/minha-conta', [UsuarioController::class, 'pegarDadosConta'])->add(AutenticacaoMiddleware::class);
    $app->post('/minha-conta', [UsuarioController::class, 'editarDados'])->add(AutenticacaoMiddleware::class);

    $app->post('/teste', function ($request, $response) {
        $authorizationHeader = $request->getHeaderLine('Authorization');
        var_dump($authorizationHeader);
        die;
    
    });
};
