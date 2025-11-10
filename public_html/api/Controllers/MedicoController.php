<?php

namespace Api\Controllers;

use Api\Helpers\JsonResponse;
use Api\Services\MedicoService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class MedicoController
{
    public function solicitarAcesso(Request $request, Response $response)
    {
        $jsonResponse = new JsonResponse();
        $medico = $request->getAttribute('usuario');
        $paciente = $request->getParsedBody();

        $resposta = (new MedicoService())->solicitar_acesso($medico->usuario_id, $paciente['paciente_id']);
        return $jsonResponse->emitirResposta($response, ["message" => $resposta['message'], 'code' => $resposta['code']], $resposta['code']);
    }
}
