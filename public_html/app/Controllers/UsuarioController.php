<?php

namespace App\Controllers;

use App\Services\UsuarioService;
use App\Helpers\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class UsuarioController
{
    public function realizarCadastro(Request $request, Response $response): Response

    {
        $data = $request->getParsedBody();
        $jsonResponse = new JsonResponse();

        if (is_null($data) || empty($data) || !isset($data['consentimento_lgpd']) || $data['consentimento_lgpd'] == 0) {
            return   $jsonResponse->emitirResposta($response, ['mensagem' => "Não consente com a LGPD"], 400);
        }

        $resposta = (new UsuarioService())->realizarCadastro($data);


        return $jsonResponse->emitirResposta($response, ["mensagem" => $resposta['mensagem'], 'token' => $resposta['token']], $resposta['status']);
    }

    public function pegarDadosConta(Request $request, Response $response): Response
    {
        $dadosUsuario = $request->getAttribute('usuario');
        $resposta = (new UsuarioService())->buscarDados($dadosUsuario->tipo_usuario);
        $jsonResponse = new JsonResponse();
        return $jsonResponse->emitirResposta($response, ["mensagem" => $resposta['mensagem'], 'dados' => $resposta['dados']], $resposta['status']);
    }


    public function desativarPerfil(Request $request, Response $response): Response
    {
        $dadosUsuario = $request->getAttribute('usuario');
        $resposta = (new UsuarioService())->desativar($dadosUsuario->id_usuario);
        $jsonResponse = new JsonResponse();

        return $jsonResponse->emitirResposta($response, ["mensagem" => $resposta['mensagem'], $resposta['status']]);
    }
}
