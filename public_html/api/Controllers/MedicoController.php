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
        $paciente = $request->getParsedBody()['paciente_id'];

        $resposta = (new MedicoService())->solicitar_acesso($medico->usuario_id, $paciente);
        return $jsonResponse->emitirResposta($response, ["message" => $resposta['message'], 'code' => $resposta['code']], $resposta['code']);
    }

    public function buscarPaciente(Request $request, Response $response){
        $jsonResponse = new JsonResponse();
        $dado_pesquisa = $request->getParsedBody()['user'];


        $resposta = (new MedicoService())->buscar_usuario($dado_pesquisa);
        return $jsonResponse->emitirResposta($response, ['message'=> $resposta['message'],'code'=> $resposta['code'],'data'=> $resposta['data']], $resposta['code']);
    }
}
