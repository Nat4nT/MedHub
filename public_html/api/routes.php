<?php

use Api\Controllers\LoginController;
use Api\Controllers\UsuarioController;
use Api\Controllers\CategoriaController;
use Api\Middlewares\AutenticacaoMiddleware;
use Slim\App;


// caminho :C:\Users\Natan\Documents\Natan\TADS\Codigos\MedHUBApi\public_html\Api\routes.php

return function (App $app) {

    $app->post('/login', [LoginController::class, 'realizarLogin']);
    $app->post('/registrar', [UsuarioController::class, 'realizarCadastro']);


    $app->group('/minha-conta', function ($user) {
        $user->get('', [UsuarioController::class, 'pegarDadosConta']);
        $user->post('', [UsuarioController::class, 'editarUsuario']);
        $user->post('/deletar', [UsuarioController::class, 'desativarPerfil']);
    })->add(AutenticacaoMiddleware::class);


    $app->group('/categoria', function ($cat) {
        $cat->get('', [CategoriaController::class, 'index']);
        $cat->post('', [CategoriaController::class, 'create']);
        $cat->post('/deletar', [CategoriaController::class, 'delete']);
    })->add(AutenticacaoMiddleware::class);

    $app->post('/teste', function ($request, $response) {
        $authorizationHeader = $request->getHeaderLine('Authorization');
        var_dump($authorizationHeader);
        die;
    });
};
