<?php

namespace App\Controller;

use App\Helpers\CadastroService;
use App\Helpers\JsonResponse;
use Psr\Http\Message\MessageInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class UsuarioController
{
    public function realizarCadastro(Request $request, Response $response)
    {
        $data = $request->getParsedBody();
        $resposta = (new CadastroService())->realizarCadastro($data);
        return (new JsonResponse())->emitirResposta($response, ["mensagem" => $resposta['mensagem'], 'token' => $resposta['token']], $resposta['status']);
    }
}
