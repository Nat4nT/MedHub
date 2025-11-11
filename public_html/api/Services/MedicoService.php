<?php

namespace Api\Services;

use Api\Helpers\Criptografia;
use Api\Models\AutorizacaoAcessoModel;
use Api\Models\UsuarioModel;

class MedicoService
{

    public function solicitar_acesso($medico_id, $paciente_id)
    {
        $data = [
            "medico_id" => $medico_id,
            "paciente_id" => $paciente_id
        ];

        if ($medico_id && $paciente_id) {
            (new AutorizacaoAcessoModel())->AddData($data);
            return ['code' => 200, 'message' => 'Solicitação enviada!'];
        } else {
            return ['code' => 400, 'message' => "Dados Invalidos"];
        }
    }

    public function buscar_usuario($dado)
    {
        $usuario = (new UsuarioModel())->buscarUsuarioPorPerfil((new Criptografia())->encriptarDado($dado));
        if ($usuario) {
            return ["code" => 200, "message" => "Perfil Encontrado", "data" => $usuario];
        } else {
            return ["code" => 400, "message" => "Perfil não encontrado", "data" => []];
        }
    }
}
