<?php

use App\Controllers\LoginController;
use App\Controllers\UsuarioController;
use App\Middlewares\AutenticacaoMiddleware;
use Slim\App;


// caminho :C:\Users\Natan\Documents\Natan\TADS\Codigos\MedHUBApi\public_html\app\routes.php

return function (App $app) {

    $app->post('/login', [LoginController::class, 'realizarLogin']);

    $app->post('/registrar', [UsuarioController::class, 'realizarCadastro']);

    $app->group('/minha-conta', function ($group) {
        $group->get('', [UsuarioController::class, 'pegarDadosConta']);
        $group->post('', [UsuarioController::class, 'editarUsuario']);
        $group->post('/deletar', [UsuarioController::class, 'desativarPerfil']);
    })->add(AutenticacaoMiddleware::class);

    $app->post('/teste', function ($request, $response) {
        $authorizationHeader = $request->getHeaderLine('Authorization');
        var_dump($authorizationHeader);
        die;
    });
};
