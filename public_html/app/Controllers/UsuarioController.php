<?php

namespace App\Controllers;

use App\Services\CadastroService;
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

        $resposta = (new CadastroService())->realizarCadastro($data);


        return $jsonResponse->emitirResposta($response, ["mensagem" => $resposta['mensagem'], 'token' => $resposta['token']], $resposta['status']);
    }
}
